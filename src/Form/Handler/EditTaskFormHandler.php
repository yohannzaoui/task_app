<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 22/03/19
 * Time: 13:22
 */

namespace App\Form\Handler;


use App\Entity\Task;
use App\Service\FileUploader;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use App\Event\FileRemoverEvent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


/**
 * Class EditTaskFormHandler
 *
 * @package App\FormHandler
 */
class EditTaskFormHandler
{
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $eventDispatcher;

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
     * EditTaskFormHandler constructor.
     *
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Doctrine\Common\Persistence\ObjectManager                  $manager
     * @param \App\Service\FileUploader                                   $fileUploader
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface  $flashMessage
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        ObjectManager $manager,
        FileUploader $fileUploader,
        SessionInterface $flashMessage
    ){
        $this->eventDispatcher = $eventDispatcher;
        $this->manager = $manager;
        $this->fileUploader = $fileUploader;
        $this->flashMessage = $flashMessage;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \App\Entity\Task                      $task
     *
     * @return bool
     * @throws \Exception
     */
    public function handle(FormInterface $form, Task $task)
    {
        if ($form->isSubmitted() && $form->isValid()){
            if ($form->getData()->getFile()){
                $this->eventDispatcher->dispatch(
                    FileRemoverEvent::NAME,
                    new FileRemoverEvent($task->getImage()
                    )
                );

                $fileName = $this->fileUploader->upload($form->getData()->getFile());
                $task->setImage($fileName);
            }

            $task->updateDate();

            $this->manager->flush();

            $this->flashMessage->getFlashBag()->add(
                'success',
                'Tâche modifier'
            );

            return true;
        }

        return false;
    }
}