<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Helper\Email;
use App\Service\TokenGenerator;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class RegistrationController
 *
 * @package App\Controller
 */
class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     *
     * @param \Symfony\Component\HttpFoundation\Request                             $request
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder
     * @param \App\Service\TokenGenerator                                           $tokenGenerator
     * @param \App\Helper\Email                                                     $email
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, TokenGenerator $tokenGenerator, Email $email): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $token = $tokenGenerator::generate();
            $user ->setToken($token);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $email->emailRegister($form->getData()->getEmail(), $token, $user->getId());

            $this->addFlash(
                'success',
                'Un email vous a été envoyé pour confirmer la création de votre compte'
            );

            return $this->redirectToRoute('app_register');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/confirm/{token}/{id}", name="confirm", methods={"GET"})
     *
     * @param                                            $id
     * @param \Symfony\Component\HttpFoundation\Request  $request
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function confirm($id, Request $request, ObjectManager $manager)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['id' => $id]);

        if (!$user){
            throw new \Exception('utilisateur inconnu');
        }

        if ($request->get('token') == $user->getToken()){
            $user->validate();

            $manager->flush();

            $this->addFlash(
                'success',
                'Votre compte à bien été créer'
            );

        } else {
            throw new \Exception('Erreur : le token de validation est incorrect ou le compte à déja été validé');
        }
        return $this->redirectToRoute('app_login');
    }
}
