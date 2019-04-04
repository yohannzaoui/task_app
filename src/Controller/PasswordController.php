<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 28/02/19
 * Time: 20:02
 */

namespace App\Controller;

use Exception;
use App\Entity\User;
use App\Event\EmailPasswordEvent;
use App\Form\EmailFormType;
use App\Form\PasswordFormType;
use App\Service\TokenGenerator;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class PasswordController
 *
 * @package App\Controller
 */
class PasswordController extends AbstractController
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $manager;

    /**
     * @var \Symfony\Contracts\Translation\TranslatorInterface
     */
    private $translator;

    /**
     * PasswordController constructor.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager         $manager
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator
     */
    public function __construct(
        ObjectManager $manager,
        TranslatorInterface $translator
    ){
        $this->manager = $manager;
        $this->translator = $translator;
    }

    /**
     * @Route(path="/checkUser", name="check_user", methods={"GET", "POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request                   $request
     * @param \App\Service\TokenGenerator                                 $tokenGenerator
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function checkUser(Request $request, TokenGenerator $tokenGenerator, EventDispatcherInterface $eventDispatcher): Response
    {
        $form = $this->createForm(EmailFormType::class)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->findOneBy(['email' => $form->getData()['email']]);

            if (!$user){
                throw new Exception('utilisateur inconnu');
            }

            $token = $tokenGenerator::generate();

            $user->setToken($token);

            $this->manager->flush();

            $eventDispatcher->dispatch(
                EmailPasswordEvent::NAME,
                new EmailPasswordEvent(
                    $user->getEmail(),
                    $token,
                    $user->getId()
                )
            );

            $this->addFlash(
                'success',
                'Un email pour la récupération de votre mot de passe vous a été envoyé'
            );

            return $this->redirectToRoute('check_user');
        }

        $title = $this->translator->trans('Recover my password');

        return $this->render('password/checkUser.html.twig', [
            'form' => $form->createView(),
            'title' => $title
        ]);
    }


    /**
     * @Route(path="/confirm/password/{token}/{id}", name="confirm_password", methods={"GET", "POST"})
     *
     * @param                                                                       $id
     * @param \Symfony\Component\HttpFoundation\Request                             $request
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function confirm(string $id, Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        if (!$user){
            throw new Exception('utilisateur inconnu');
        }

        if ($request->get('token') == $user->getToken()){
            $form = $this->createForm(PasswordFormType::class)
                ->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()){
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                $user->resetToken();

                $this->manager->flush();

                $this->addFlash(
                    'success',
                    'Votre mot de passe a bien été modifier'
                );

                return $this->redirectToRoute('app_login');
            }

            $title = $this->translator->trans('Recover my password');

            return $this->render('password/confirm.html.twig', [
                'form' => $form->createView(),
                'title' => $title
            ]);
        }

        if ($request->get('token') != $user->getToken()){
            throw new Exception('Invalid token');
        }
    }
}