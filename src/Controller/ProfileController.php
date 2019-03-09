<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditProfileFormType;
use App\Form\PasswordFormType;
use App\Service\FileRemover;
use App\Service\FileUploader;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class ProfileController
 *
 * @package App\Controller
 */
class ProfileController extends AbstractController
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $manager;

    /**
     * @var \App\Service\FileRemover
     */
    private $fileRemover;

    /**
     * ProfileController constructor.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     * @param \App\Service\FileRemover                   $fileRemover
     */
    public function __construct(
        ObjectManager $manager,
        FileRemover $fileRemover
    ){
        $this->manager = $manager;
        $this->fileRemover = $fileRemover;
    }

    /**
     * @Route("/profile", name="profile", methods={"GET"})
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
     * @Route("/profile/{id}", name="edit_profile", methods={"GET", "POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Service\FileUploader                 $fileUploader
     * @param                                           $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function edit(Request $request, FileUploader $fileUploader, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        $form = $this->createForm(EditProfileFormType::class, $user)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            if ($form->getData()->getFile()){
                $this->fileRemover->deleteFile($this->getUser()->getImage());
                $fileName = $fileUploader->upload($form->getData()->getFile());
                $user->setImage($fileName);
            }

            $user->updateDate();

            $this->manager->flush();

            $this->addFlash('success', 'Profile edited');

            return $this->redirectToRoute('profile');
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
            'title' => 'Modifier mon profil'
        ]);
    }

    /**
     * @Route("/profile/password/{id}", name="edit_password", methods={"GET", "POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request                             $request
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder
     * @param                                                                       $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function editPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        $form = $this->createForm(PasswordFormType::class)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->updateDate();

            $this->manager->flush();

            $this->addFlash('success', 'Password edited');

            return $this->redirectToRoute('profile');
        }
        return $this->render('profile/edit_password.html.twig', [
            'form' => $form->createView(),
            'title' => 'Modifier mon mot de passe'
        ]);
    }

    /**
     * @Route(path="/delete/avatar/", name="delete_avatar", methods={"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAvatar(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $this->fileRemover->deleteFile($this->getUser()->getImage());

        $this->getUser()->setImage(null);

        $this->manager->flush();

        return $this->redirectToRoute('profile');
    }
}