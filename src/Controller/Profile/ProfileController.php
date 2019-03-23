<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 23/03/19
 * Time: 19:43
 */

namespace App\Controller\Profile;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class ProfileController
 *
 * @package App\Controller\Profile
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
}