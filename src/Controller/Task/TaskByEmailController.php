<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 22/03/19
 * Time: 08:53
 */

namespace App\Controller\Task;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Event\TaskByEmailEvent;

/**
 * Class TaskByEmailController
 *
 * @package App\Controller\Task
 */
class TaskByEmailController extends AbstractController
{
    /**
     * @Route(path="/send/task/byEmail", name="send_task_byEmail", methods={"GET", "POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request                   $request
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function sendByEmail(Request $request, EventDispatcherInterface $eventDispatcher): Response
    {
        $task = $this->getDoctrine()
            ->getRepository(Task::class)
            ->find($request->request->get('task_id'));

        if (!$task){
            throw new \Exception('Pas de tâche avec cet ID');
        }

        if ($this->isCsrfTokenValid('email', $request->request->get('_csrf_token'))){
            $this->denyAccessUnlessGranted('send', $task);

            $eventDispatcher->dispatch(
                TaskByEmailEvent::NAME,
                new TaskByEmailEvent(
                    $request->request->get('email'),
                    $task->getTitle(),
                    $task->getContent()
                )
            );

            $this->addFlash(
                'success',
                'Votre tâche à bien été envoyer à : '.$request->request->get('email')
            );
            return $this->redirectToRoute('show_task', ['id' => $task->getId()]);
        }
    }
}