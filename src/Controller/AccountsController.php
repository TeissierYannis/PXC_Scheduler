<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\PackAccount;
use App\Form\AccountAddType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AccountsController extends AbstractController
{
    /**
     * @Route("manage/accounts", name="logged_manage_accounts")
     */
    public function index(Request $request)
    {

        $packAccount = new PackAccount();

        $accounts_form = $this->createForm(AccountAddType::class, $packAccount);
        $accounts_form->handleRequest($request);

        if($accounts_form->isSubmitted() && $accounts_form->isValid()){

            $manager = $this->getDoctrine()->getManager();

            $accountUsername = $accounts_form->get('AccountUsername')->getData();
            $accountLogin = $accounts_form->get('AccountLogin')->getData();
            $accountPassword = $accounts_form->get('AccountPassword')->getData();
            $accountPacksQuantity = $accounts_form->get('Pack_Quantity')->getData();
            $accountLevel = $accounts_form->get('AccountLevel')->getData();

            $packAccount->setAccountUsername($accountUsername);
            $packAccount->setAccountLogin($accountLogin);
            $packAccount->setAccountPassword($accountPassword);
            $packAccount->setPackQuantity($accountPacksQuantity);
            $packAccount->setAccountLevel($accountLevel);
            $packAccount->setUserId($this->getUser());

            $manager->persist($packAccount);
            $manager->flush();

            $this->addFlash('notice', 'Account successfully added');

        }


        return $this->render('manage/packs_add.html.twig', [
            'accounts_form' => $accounts_form->createView()
        ]);
    }

    /**
     * @Route("manage/delete/account/{id}", name="logged_manage_delete_account")
     */
    public function deleteAccount(int $id){

        $doctrine = $this->getDoctrine();
        $manager = $doctrine->getManager();

        $packAccountRepo = $doctrine->getRepository(PackAccount::class);
        $eventRepo = $doctrine->getRepository(Event::class);

        $packAccount = $packAccountRepo->find($id);
        $events = $eventRepo->findBy(['accoubt' => $packAccount->getUserId()]);

        foreach ($events as $event){
            $manager->remove($event);
        }

        $manager->remove($packAccount);
        $manager->flush();

        return $this->redirectToRoute("logged_profile");
    }
}
