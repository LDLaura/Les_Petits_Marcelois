<?php

namespace App\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app.login', methods: ['GET', 'POST'])]
    public function index(AuthenticationUtils $authUtils): Response
    {
        return $this->render('Security/login.html.twig', [
            // get the login error if there is one
            'error' => $authUtils->getLastAuthenticationError(),
             // last username entered by the user
            'lastUsername' => $authUtils->getLastUsername(),
        ]);
    }
}
