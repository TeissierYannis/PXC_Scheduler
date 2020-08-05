<?php

namespace App\Controller;

use App\Entity\PackAccount;
use App\Entity\User;
use App\Form\PasswordEditingFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfileController extends AbstractController
{
    /**
     * @Route("user/profile", name="logged_profile")
     */
    public function index()
    {
        $user = $this->getUser()->getUsername();

        $packs = $this->getDoctrine()->getRepository(PackAccount::class)->findBy([
            'UserId' => $this->getUser()
            ]);

        dump($packs);

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'packs' => $packs
        ]);
    }

    /**
     * @Route("user/manage/profile", name="logged_manage_profile")
     * @param Request $request
     * @return Response
     */
    public function manage_profile(Request $request, UserPasswordEncoderInterface $encoder){

        $user = new User();

        $password_form = $this->createForm(PasswordEditingFormType::class, $user);
        $password_form->handleRequest($request);

        if($password_form->isSubmitted() && $password_form->isValid()){

            $manager = $this->getDoctrine()->getManager();

            $current_user = $this->getUser();

            $old_password = $password_form->get('password')->getData();
            $new_password = $password_form->get('new_password')->getData();
            $repeat_password =  $password_form->get('repeat_password')->getData();

            if($encoder->isPasswordValid($current_user, $old_password)){

                if($new_password === $repeat_password){

                    $new_encoded_password = $encoder->encodePassword($current_user, $new_password);

                    $current_user->setPassword($new_encoded_password);

                    $manager->persist($current_user);
                    $manager->flush();

                    $this->addFlash('notice', 'Password has been changed');
                }else{
                    $this->addFlash('error', 'Password do not match');
                }


            }else{
                $this->addFlash('error', 'The current password is incorrect');
            }

        }

        return $this->render('manage/profile.html.twig', [
            'password_form' => $password_form->createView()
        ]);
    }
}
