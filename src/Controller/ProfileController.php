<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditProfileFormType;
use App\Form\PasswordFormType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/profile", name="profile", methods={"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show()
    {
        return $this->render('profile/index.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/profile/{id}", name="edit_profile", methods={"GET", "POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request  $request
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     * @param                                            $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function edit(Request $request, ObjectManager $manager, $id)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        $form = $this->createForm(EditProfileFormType::class, $user)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user->updateDate();

            $manager->flush();

            $this->addFlash('success', 'Profile edited');

            return $this->redirectToRoute('profile');
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/profile/password/{id}", name="edit_password", methods={"GET", "POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request                             $request
     * @param \Doctrine\Common\Persistence\ObjectManager                            $manager
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder
     * @param                                                                       $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function editPassword(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $passwordEncoder, $id)
    {
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

            $manager->flush();

            $this->addFlash('success', 'Password edited');

            return $this->redirectToRoute('profile');
        }
        return $this->render('profile/edit_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
