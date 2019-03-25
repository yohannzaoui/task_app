<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 22/03/19
 * Time: 09:07
 */

namespace App\Controller\Task;

use App\Entity\Task;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Event\TaskToMyEmailEvent;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class TaskToMyEmailController
 *
 * @package App\Controller\Task
 */
class TaskToMyEmailController extends AbstractController
{
    /**
     * @Route(path="/send/task/myEmail/{id}", name="send_task_myEmail", methods={"GET"})
     *
     * @param                                                             $id
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function sendToMyEmail(string $id, EventDispatcherInterface $eventDispatcher): Response
    {
        $task = $this->getDoctrine()
            ->getRepository(Task::class)
            ->find($id);

        $this->denyAccessUnlessGranted('send', $task);

        $eventDispatcher->dispatch(
            TaskToMyEmailEvent::NAME,
            new TaskToMyEmailEvent(
                $this->getUser()->getEmail(),
                $task->getTitle(),
                $task->getContent()
            ));

        $this->addFlash(
            'success',
            'Tâche envoyer sur votre adresse email : '.$this->getUser()->getEmail()
        );

        return $this->redirectToRoute('tasks');
    }
}