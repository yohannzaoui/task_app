<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 22/03/19
 * Time: 08:46
 */

namespace App\Controller\Task;

use App\Entity\Task;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class TasksController
 *
 * @package App\Controller\Task
 */
class TasksController extends AbstractController
{
    /**
     * @Route(path="/", name="tasks", methods={"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $tasks = $this->getDoctrine()
            ->getRepository(Task::class)
            ->findBy([
                'done'=> false,
                'pin' => false,
                'author' => $this->getUser()
            ]);

        $tasksPin = $this->getDoctrine()
            ->getRepository(Task::class)
            ->findBy([
                'done'=> false,
                'pin' => true,
                'author' => $this->getUser()
            ]);

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
            'tasksPin' => $tasksPin,
            'title' => 'Mes tâches'

        ]);
    }
}