<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class SecurityController
 *
 * @package App\Controller
 */
class SecurityController extends AbstractController
{
    /**
     * @Route(path="/login", name="app_login", methods={"GET", "POST"})
     *
     * @param \Symfony\Component\Security\Http\Authentication\AuthenticationUtils $authenticationUtils
     * @param \Symfony\Contracts\Translation\TranslatorInterface                  $translator
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(AuthenticationUtils $authenticationUtils, TranslatorInterface $translator): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        $title = $translator->trans('Sign in');

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'title' => $title
        ]);
    }
}
