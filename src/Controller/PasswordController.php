<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 28/02/19
 * Time: 20:02
 */

namespace App\Controller;


use App\Entity\User;
use App\Form\EmailFormType;
use App\Service\TokenGenerator;
use App\Helper\Email;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PasswordController
 *
 * @package App\Controller
 */
class PasswordController extends AbstractController
{
    /**
     * @Route("/checkUser", name="check_user", methods={"GET", "POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request  $request
     * @param \App\Service\TokenGenerator                $tokenGenerator
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     * @param \App\Helper\Email                          $email
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function checkUser(Request $request, TokenGenerator $tokenGenerator, ObjectManager $manager, Email $email)
    {
        $form = $this->createForm(EmailFormType::class)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->findOneBy(['email' => $form->getData()['email']]);

            if (!$user){
                return $this->render('error/error.html.twig', [
                    'error' => 'Erreur : Ce compte n\'éxiste pas'
                ]);
            }

            $token = $tokenGenerator::generate();

            $user->setToken($token);

            $manager->flush();

            $email->emailPassword($user->getEmail(), $token, $user->getId());

            $this->addFlash('success', 'Votre mot de passe a bien été mofidier');

            return $this->redirectToRoute('confirm_password');
        }

        return $this->render('password/checkUser.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/confirm/password/{token}/{id}", name="confirm_password")
     */
    public function confirm()
    {

    }

    public function reset()
    {

    }
}