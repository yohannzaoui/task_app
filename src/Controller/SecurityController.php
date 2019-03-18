<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;

/**
 * Class SecurityController
 *
 * @package App\Controller
 */
class SecurityController
{
    /**
     * @Route(path="/login", name="app_login", methods={"GET", "POST"})
     *
     * @param \Symfony\Component\Security\Http\Authentication\AuthenticationUtils $authenticationUtils
     * @param \Twig\Environment                                                   $twig
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function login(AuthenticationUtils $authenticationUtils, Environment $twig): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return new Response($twig->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'title' => 'Connectez-vous'
        ]));
    }
}
