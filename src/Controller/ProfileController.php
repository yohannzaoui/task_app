<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 28/03/19
 * Time: 16:21
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\Handler\EditPasswordFormHandler;
use App\Form\Handler\EditProfileFormHandler;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Persistence\ObjectManager;
use App\Form\PasswordFormType;
use App\Form\EditProfileFormType;
use App\Event\FileRemoverEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class ProfileController
 *
 * @package App\Controller
 */
class ProfileController extends AbstractController
{
    /**
     * @Route(path="/profile", name="profile", methods={"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('profile/index.html.twig', [
            'user' => $this->getUser(),
            'title' => 'Mon profil'
        ]);
    }


    /**
     * @Route(path="/edit/profile", name="edit_profile", methods={"GET", "POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Form\Handler\EditProfileFormHandler   $handler
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function edit(Request $request, EditProfileFormHandler $handler): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($this->getUser()->getId());

        $form = $this->createForm(EditProfileFormType::class, $user)
            ->handleRequest($request);

        if ($handler->handle($form, $user)){

            return $this->redirectToRoute('profile');
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
            'title' => 'Modifier mon profil'
        ]);
    }


    /**
     * @Route(path="/profile/password", name="edit_password", methods={"GET", "POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Form\Handler\EditPasswordFormHandler  $handler
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function editPassword(Request $request, EditPasswordFormHandler $handler): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($this->getUser()->getId());

        $form = $this->createForm(PasswordFormType::class)
            ->handleRequest($request);

        if ($handler->handle($form, $user)){
            return $this->redirectToRoute('profile');
        }
        return $this->render('profile/edit_password.html.twig', [
            'form' => $form->createView(),
            'title' => 'Modifier mon mot de passe'
        ]);
    }


    /**
     * @Route(path="/profile/delete/avatar/", name="delete_avatar", methods={"GET"})
     *
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Doctrine\Common\Persistence\ObjectManager                  $manager
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAvatar(EventDispatcherInterface $eventDispatcher, ObjectManager $manager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $eventDispatcher->dispatch(
            FileRemoverEvent::NAME,
            new FileRemoverEvent($this->getUser()->getImage()
            )
        );

        $this->getUser()->setImage(null);

        $manager->flush();

        return $this->redirectToRoute('profile');
    }
}