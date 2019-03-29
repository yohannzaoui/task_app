<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 28/03/19
 * Time: 16:37
 */

namespace App\Form\Handler;


use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class EditPasswordFormHandler
 *
 * @package App\FormHandler
 */
class EditPasswordFormHandler
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $manager;

    /**
     * @var \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    private $flashMessage;

    /**
     * EditPasswordFormHandler constructor.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager                            $manager
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $encoder
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface            $flashMessage
     */
    public function __construct(
        ObjectManager $manager,
        UserPasswordEncoderInterface $encoder,
        SessionInterface $flashMessage
    ){
        $this->manager = $manager;
        $this->encoder = $encoder;
        $this->flashMessage = $flashMessage;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \App\Entity\User                      $user
     *
     * @return bool
     * @throws \Exception
     */
    public function handle(FormInterface $form, User $user)
    {
        if ($form->isSubmitted() && $form->isValid()){
            $user->setPassword(
                $this->encoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->updateDate();

            $this->manager->flush();

            $this->flashMessage->getFlashBag()->add('success', 'Password edited');

            return true;
        }
        return false;
    }
}