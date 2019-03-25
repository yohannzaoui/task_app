<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 22/03/19
 * Time: 08:44
 */

namespace App\Controller\Task;

use App\Entity\Task;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class TaskController
 *
 * @package App\Controller\Task
 */
class TaskController extends AbstractController
{
    /**
     * @Route(path="/task/show/{id}", name="show_task", methods={"GET", "POST"})
     *
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function show(string $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $task = $this->getDoctrine()
            ->getRepository(Task::class)
            ->find($id);

        if (!$task){
            throw new \Exception('Pas de tâche avec cet ID');
        }

        return $this->render('task/show.html.twig', [
            'task' => $task,
            'title' => $task->getTitle()
        ]);
    }
}