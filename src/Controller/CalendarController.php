<?php

namespace App\Controller;

use App\Calendar\Calendar;
use App\Entity\Event;
use App\Form\EventAddType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendarController extends AbstractController
{

    /**
     * @Route("/user/calendar", name="logged_no_params_calendar")
     */
    public function noParamsCalendar(){
        return $this->redirectToRoute('logged_calendar', ['slug' => date('m', time()) . '-' . date('Y', time())]);
    }

    /**
     * @Route("/user/calendar/{slug}", name="logged_calendar")
     * @param string $slug
     * @return Response
     */
    public function index(string $slug)
    {

        $calendar = new Calendar();

        $events = $this->getDoctrine()->getRepository(Event::class)->findAll();

        $month = intval(explode('-', $slug)[0]);
        $year = intval(explode('-', $slug)[1]);

        if(!is_int($month) || $month < 1){
            $month = date('m', time());
        }

        if(!is_int($year) || $year < 1){
            $year = date('Y', time());
        }


        return $this->render('calendar/index.html.twig', [
            'response' => $calendar->createView($month, $year),
            'month' => $month,
            'year' => $year,
            'events' => $events
        ]);
    }

    /**
     * @Route("/user/calendar/event/add", name="logged_calendar_add_event")
     * @param Request $request
     * @return Response
     */
    public function addEvent(Request $request){

        $form = $this->createForm(EventAddType::class);
        $form->handleRequest($request);

        $event = new Event();

        $manager = $this->getDoctrine()->getManager();

        if($form->isSubmitted() && $form->isValid()){

            $event->setAccount($form->get('account')->getData());
            $event->setSchedulerDatetime($form->get('scheduler_datetime')->getData());

            $manager->persist($event);
            $manager->flush();

            return $this->redirectToRoute('logged_no_params_calendar');
        }

        return $this->render('calendar/addEvent.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
