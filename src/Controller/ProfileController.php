<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("user/profile", name="logged_profile")
     */
    public function index()
    {
        $user = $this->getUser()->getUsername();

        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }
}
