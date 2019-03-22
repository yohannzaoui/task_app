<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 22/03/19
 * Time: 09:45
 */

namespace App\FormHandler;


use App\Entity\Task;
use App\Service\FileUploader;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class CreateTaskControllerFormHandler
 *
 * @package App\FormHandler
 */
class CreateTaskControllerFormHandler
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $manager;

    /**
     * @var \App\Service\FileUploader
     */
    private $fileUploader;

    /**
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    private $flashMessage;

    /**
     * @var \Symfony\Component\Security\Core\Security
     */
    private $security;

    /**
     * CreateTaskControllerFormHandler constructor.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager                 $manager
     * @param \App\Service\FileUploader                                  $fileUploader
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $flashMessage
     * @param \Symfony\Component\Security\Core\Security                  $security
     */
    public function __construct(
        ObjectManager $manager,
        FileUploader $fileUploader,
        SessionInterface $flashMessage,
        Security $security
    ){
        $this->manager = $manager;
        $this->fileUploader = $fileUploader;
        $this->flashMessage = $flashMessage;
        $this->security = $security;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \App\Entity\Task                      $task
     *
     * @return bool
     */
    public function handle(FormInterface $form, Task $task)
    {
        if ($form->isSubmitted() && $form->isValid()){
            if ($form->getData()->getFile()){
                $fileName = $this->fileUploader->upload($form->getData()->getFile());
                $task->setImage($fileName);
            }

            $task->setAuthor($this->security->getUser());

            $this->manager->persist($task);
            $this->manager->flush();

            $this->flashMessage->getFlashBag()->add('success',
                'Tâche ajouter'
            );

            return true;
        }
        return false;
    }
}