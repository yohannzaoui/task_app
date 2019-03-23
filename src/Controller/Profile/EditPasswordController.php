<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 23/03/19
 * Time: 21:19
 */

namespace App\Controller\Profile;


use App\Entity\User;
use App\Form\PasswordFormType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class EditPasswordController
 *
 * @package App\Controller\Profile
 */
class EditPasswordController extends AbstractController
{
    /**
     * @Route(path="/profile/password/{id}", name="edit_password", methods={"GET", "POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request                             $request
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder
     * @param \Doctrine\Common\Persistence\ObjectManager                            $manager
     * @param                                                                       $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function editPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder, ObjectManager $manager, $id): Response
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

            $manager->flush();

            $this->addFlash('success', 'Password edited');

            return $this->redirectToRoute('profile');
        }
        return $this->render('profile/edit_password.html.twig', [
            'form' => $form->createView(),
            'title' => 'Modifier mon mot de passe'
        ]);
    }
}