<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 *
 * @package App\Controller
 */
class UserController extends AbstractController
{
    /**
     * @Route(path="/user", name="user_index", methods={"GET"})
     *
     * @param \App\Repository\UserRepository $userRepository
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'title' => 'Liste des membres'
        ]);
    }

    /**
     * @Route(path="/user/{id}", name="user_delete", methods={"GET"})
     *
     * @param \App\Entity\User $user
     * @param                  $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(User $user, $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($id) {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($user);
            $manager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
