<?php

namespace App\Controller;

use App\Entity\User;
use App\Event\EmailRegisterEvent;
use App\Form\RegistrationFormType;
use App\Service\TokenGenerator;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $manager;

    /**
     * RegistrationController constructor.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route(path="/register", name="app_register", methods={"GET", "POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request                             $request
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder
     * @param \App\Service\TokenGenerator                                           $tokenGenerator
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface           $eventDispatcher
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        TokenGenerator $tokenGenerator,
        EventDispatcherInterface $eventDispatcher
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $token = $tokenGenerator::generate();
            $user ->setToken($token);

            $this->manager->persist($user);
            $this->manager->flush();

            $eventDispatcher->dispatch(
                EmailRegisterEvent::NAME,
                new EmailRegisterEvent(
                    $form->getData()->getEmail(),
                    $token,
                    $user->getId()
                )
            );

            $this->addFlash(
                'success',
                'Un email vous a été envoyé pour confirmer la création de votre compte'
            );

            return $this->redirectToRoute('app_register');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'title' => 'Inscription'
        ]);
    }

    /**
     * @Route("/confirm/{token}/{id}", name="confirm", methods={"GET"})
     *
     * @param                                            $id
     * @param \Symfony\Component\HttpFoundation\Request  $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function confirm(string $id, Request $request): Response
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['id' => $id]);

        if (!$user){
            throw new \Exception('utilisateur inconnu');
        }

        if ($request->get('token') == $user->getToken()){
            $user->validate();

            $this->manager->flush();

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
