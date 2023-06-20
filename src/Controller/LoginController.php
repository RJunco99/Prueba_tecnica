<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
class LoginController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
            // get the login error if there is one
           $error = $authenticationUtils->getLastAuthenticationError();
    
            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();
    
            return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,    
        ]);
    }
    #[Route('/logout', name: 'app_logout', methods: ['POST'])]
    public function logout(): RedirectResponse
{
    throw new \Exception('Don\'t forget to activate logout in security.yaml');

    return $this->redirectToRoute('app_login');
}

    #[Route('admin/login', name: 'admin_login')]
    public function adminLogin(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
    
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        
        return $this->render('admin/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,   
        ]);
    }
}
