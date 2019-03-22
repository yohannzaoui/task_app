<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 22/03/19
 * Time: 08:38
 */

namespace App\Controller\Task;

use App\Entity\Task;
use App\Form\EditTaskType;
use App\Service\FileUploader;
use App\Event\FileRemoverEvent;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class EditTaskController
 *
 * @package App\Controller\Task
 */
class EditTaskController extends AbstractController
{
    /**
     * @Route(path="/task/edit/{id}", name="edit_task", methods={"GET", "POST"})
     *
     * @param                                                             $id
     * @param \Symfony\Component\HttpFoundation\Request                   $request
     * @param \Doctrine\Common\Persistence\ObjectManager                  $manager
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \App\Service\FileUploader                                   $fileUploader
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function edit($id, Request $request, ObjectManager $manager, EventDispatcherInterface $eventDispatcher, FileUploader $fileUploader): Response
    {
        $task = $this->getDoctrine()
            ->getRepository(Task::class)
            ->find($id);

        if (!$task){
            throw new \Exception('Pas de tâche avec cet ID');
        }

        $form = $this->createForm(EditTaskType::class, $task)
            ->handleRequest($request);

        $this->denyAccessUnlessGranted('edit', $task);


        if ($form->isSubmitted() && $form->isValid()){
            if ($form->getData()->getFile()){
                $eventDispatcher->dispatch(
                    FileRemoverEvent::NAME,
                    new FileRemoverEvent($task->getImage()
                    )
                );

                $fileName = $fileUploader->upload($form->getData()->getFile());
                $task->setImage($fileName);
            }

            $task->updateDate();

            $manager->flush();

            $this->addFlash(
                'success',
                'Tâche modifier'
            );

            return $this->redirectToRoute('show_task', ['id' => $task->getId()]);
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'title' => 'Modifier la tâche'
        ]);
    }
}