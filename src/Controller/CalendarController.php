<?php

namespace App\Controller;

use App\Calendar\Calendar;
use App\Entity\Event;
use App\Entity\PackAccount;
use App\Form\EventAddType;
use App\Repository\PackAccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @Route("/user/calendar/previous/{slug}", name="logged_previous_calendar")
     * @param string $slug
     * @return RedirectResponse
     */
    public function previousCalendar(string $slug){
        $month = intval(explode('-', $slug)[0]);
        $year = intval(explode('-', $slug)[1]);

        if($month == 1){
            $month = 12;
            $year -= 1;
        }else{
            $month -= 1;
        }

        return $this->redirectToRoute('logged_calendar', ['slug' => $month . '-' . $year]);

    }

    /**
     * @Route("/user/calendar/next/{slug}", name="logged_next_calendar")
     * @param string $slug
     * @return RedirectResponse
     */
    public function nextCalendar(string $slug){
        $month = intval(explode('-', $slug)[0]);
        $year = intval(explode('-', $slug)[1]);

        if($month == 12){
            $month = 1;
            $year += 1;
        }else{
            $month += 1;
        }

        return $this->redirectToRoute('logged_calendar', ['slug' => $month . '-' . $year]);
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

        $month = @intval(explode('-', $slug)[0]);
        $year = @intval(explode('-', $slug)[1]);

        if(!str_contains($slug, '-')){
            $month = date('m', time());
            $year = date('Y', time());
        }else{
            if(!is_int($month) || $month < 1 || $month > 12){
                $month = date('m', time());
            }

            if(!is_int($year) || $year < 1){
                $year = date('Y', time());
            }
        }

        $user_events = [];

        $i = 0;
        foreach ($events as $event){
            if($event->getAccount()->getUserId() == $this->getUser()){
                $user_events[$i] = $event;
                $i++;
            }
        }

        return $this->render('calendar/index.html.twig', [
            'response' => $calendar->createView($month, $year),
            'month' => $month,
            'year' => $year,
            'events' => $user_events
        ]);
    }

    /**
     * @Route("/user/calendar/event/add", name="logged_calendar_add_event")
     * @param Request $request
     * @return Response
     */
    public function addEvent(Request $request){

        $r = $this->getDoctrine()->getRepository(PackAccount::class);

        $current_user = $this->getUser();

        dump($r->findBy(['UserId' => $current_user]));


        $form = $this->createForm(EventAddType::class, null, ['user' => $this->getUser()]);
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
