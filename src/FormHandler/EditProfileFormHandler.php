<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 23/03/19
 * Time: 19:46
 */

namespace App\FormHandler;


use App\Entity\User;
use App\Service\FileUploader;
use App\Event\FileRemoverEvent;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class EditProfileFormHandler
 *
 * @package App\FormHandler
 */
class EditProfileFormHandler
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $manager;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    private $flashMessage;

    /**
     * @var \App\Service\FileUploader
     */
    private $fileUploader;

    /**
     * @var \Symfony\Component\Security\Core\Security
     */
    private $security;

    /**
     * EditProfileFormHandler constructor.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager                  $manager
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface  $flashMessage
     * @param \App\Service\FileUploader                                   $fileUploader
     * @param \Symfony\Component\Security\Core\Security                   $security
     */
    public function __construct(
        ObjectManager $manager,
        EventDispatcherInterface $eventDispatcher,
        SessionInterface $flashMessage,
        FileUploader $fileUploader,
        Security $security
    ){
        $this->manager = $manager;
        $this->eventDispatcher = $eventDispatcher;
        $this->flashMessage = $flashMessage;
        $this->fileUploader = $fileUploader;
        $this->security = $security;
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
            if ($form->getData()->getFile()){
                $this->eventDispatcher->dispatch(
                    FileRemoverEvent::NAME,
                    new FileRemoverEvent($this->security->getUser()->getImage())
                );

                $fileName = $this->fileUploader->upload($form->getData()->getFile());

                $user->setImage($fileName);
            }

            $user->updateDate();

            $this->manager->flush();

            $this->flashMessage->getFlashBag()->add('success', 'Profile edited');

            return true;
        }
        return false;
    }
}