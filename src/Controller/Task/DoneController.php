<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 22/03/19
 * Time: 09:09
 */

namespace App\Controller\Task;

use App\Entity\Task;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class DoneController
 *
 * @package App\Controller\Task
 */
class DoneController extends AbstractController
{
    /**
     * @Route(path="/task/done/{id}", name="done_task", methods={"GET"})
     *
     * @param                                            $id
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function done($id, ObjectManager $manager): Response
    {
        $task = $this->getDoctrine()
            ->getRepository(Task::class)
            ->find($id);

        if (!$task){
            throw new \Exception('Pas de tâche avec cet ID');
        }

        $this->denyAccessUnlessGranted('access', $task);

        if ($task->isDone() == true){
            $task->notDone();

            $manager->flush();

            return $this->redirectToRoute('tasks');
        }

        $task->done();
        $task->setPin(false);
        $task->doneDate();

        $manager->flush();

        return $this->redirectToRoute('view_done_task');
    }

    /**
     * @Route(path="/task/done", name="view_done_task", methods={"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function doneTask(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $tasks = $this->getDoctrine()
            ->getRepository(Task::class)
            ->findBy([
                'done'=> true,
                'author' => $this->getUser()
            ]);

        return $this->render('task/done.html.twig', [
            'tasks' => $tasks,
            'title' => 'Mes tâches terminées'
        ]);
    }
}