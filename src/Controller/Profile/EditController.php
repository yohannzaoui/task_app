<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 23/03/19
 * Time: 19:44
 */

namespace App\Controller\Profile;

use App\Entity\User;
use App\FormHandler\EditProfileFormHandler;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\EditProfileFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class EditController
 *
 * @package App\Controller\Profile
 */
class EditController extends AbstractController
{
    /**
     * @Route(path="/edit/profile", name="edit_profile", methods={"GET", "POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\FormHandler\EditProfileFormHandler   $handler
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
}