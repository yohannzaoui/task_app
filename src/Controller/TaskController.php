<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\EditTaskType;
use App\Form\TaskByEmailType;
use App\Form\TaskType;
use App\Helper\Email;
use App\Repository\TaskRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TaskController
 *
 * @package App\Controller
 */
class TaskController extends AbstractController
{
    /**
     * @var \App\Repository\TaskRepository
     */
    private $repository;

    /**
     * @var \Symfony\Component\Cache\Adapter\AdapterInterface
     */
    private $cache;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $manager;

    /**
     * @var \App\Helper\Email
     */
    private $sendEmail;

    /**
     * TaskController constructor.
     *
     * @param \App\Repository\TaskRepository                    $repository
     * @param \Symfony\Component\Cache\Adapter\AdapterInterface $cache
     * @param \Doctrine\Common\Persistence\ObjectManager        $manager
     * @param \App\Helper\Email                                 $sendEmail
     */
    public function __construct(
        TaskRepository $repository,
        AdapterInterface $cache,
        ObjectManager $manager,
        Email $sendEmail
    ){
        $this->repository = $repository;
        $this->cache = $cache;
        $this->manager = $manager;
        $this->sendEmail = $sendEmail;
    }

    /**
     * @Route(path="/", name="tasks", methods={"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function show(): Response
    {
        $tasks = $this->repository->findBy([
                'done'=> false,
                'pin' => false,
                'author' => $this->getUser()
            ]);

        $tasksPin = $this->repository->findBy([
                'done'=> false,
                'pin' => true,
                'author' => $this->getUser()
            ]);

        $items = $this->cache->getItem('tasks');

        if (!$items->isHit()){
            $items->set($tasks);
            $this->cache->save($items);
        }
        $tasks = $items->get();

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
            'tasksPin' => $tasksPin,
            'title' => 'Mes tâches'

        ]);
    }

    /**
     * @Route(path="/task/create", name="create_task", methods={"GET", "POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request         $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function create(Request $request): Response
    {
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task)
            ->handleRequest($request);

        $this->cache->deleteItem('tasks');

        if ($form->isSubmitted() && $form->isValid()){
            $task->setAuthor($this->getUser());

            $this->manager->persist($task);
            $this->manager->flush();

            $this->addFlash('success', 'Task created');



            return $this->redirectToRoute('tasks');
        }
        return $this->render('task/create.html.twig', [
            'form' =>$form->createView(),
            'title' => 'Créer une tâche'
        ]);
    }

    /**
     * @Route(path="/task/edit/{id}", name="edit_task", methods={"GET", "POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request         $request
     * @param                                                   $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function edit(Request $request, $id): Response
    {
        $task = $this->repository->find($id);

        if (!$task){
            throw new \Exception('no task with this ID');
        }

        $form = $this->createForm(EditTaskType::class, $task)
            ->handleRequest($request);

        $this->denyAccessUnlessGranted('edit', $task);


        if ($form->isSubmitted() && $form->isValid()){

            $this->cache->deleteItem('tasks');

            $task->updateDate();

            $this->manager->flush();

            $this->addFlash(
                'success',
                'Task edited'
            );

            return $this->redirectToRoute('tasks');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'title' => 'Modifier la tâche'
        ]);
    }

    /**
     * @Route(path="/task/delete/{id}", name="delete_task", methods={"GET"})
     *
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function delete($id): Response
    {
        $task = $this->repository->find($id);

        if (!$task){
            throw new \Exception('no task with this ID');
        }

        $this->cache->deleteItem('tasks');

        $this->denyAccessUnlessGranted('delete', $task);

        $this->manager->remove($task);
        $this->manager->flush();

        $this->addFlash(
            'success',
            'Task deleted'
        );

        return $this->redirectToRoute('tasks');
    }

    /**
     * @Route(path="/task/done/{id}", name="done_task", methods={"GET"})
     *
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function done($id): Response
    {
        $task = $this->repository->find($id);

        if (!$task){
            throw new \Exception('no task with this ID');
        }

        $this->denyAccessUnlessGranted('done', $task);

        $this->cache->deleteItem('tasks');

        if ($task->isDone() == true){
            $task->notDone();

            $this->manager->flush();

            return $this->redirectToRoute('tasks');
        }

        $task->done();
        $task->setPin(false);
        $task->doneDate();

        $this->manager->flush();

        return $this->redirectToRoute('view_done_task');
    }

    /**
     * @Route(path="/task/done", name="view_done_task", methods={"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function doneTask(): Response
    {
        $tasks = $this->repository->findBy([
                'done'=> true,
                'author' => $this->getUser()
            ]);

        return $this->render('task/done.html.twig', [
            'tasks' => $tasks,
            'title' => 'Mes tâches terminées'
        ]);
    }

    /**
     * @Route(path="/send/task/myEmail/{id}", name="send_task_myEmail", methods={"GET"})
     *
     * @param                   $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function sendTaskToMyEmail($id)
    {
        $task = $this->repository->find($id);

        $this->denyAccessUnlessGranted('send', $task);

        $this->sendEmail->taskToMyEmail(
            $this->getUser()->getEmail(),
            $task->getTitle(),
            $task->getContent()
        );

        $this->addFlash(
            'success',
            'Task send in your email : '.$this->getUser()->getEmail()
        );

        return $this->redirectToRoute('tasks');
    }

    /**
     * @Route(path="/send/task/byEmail/{id}", name="send_task_byEmail", methods={"GET", "POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param                                           $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sendTaskByEmail(Request $request ,$id): Response
    {
        $task = $this->repository->find($id);

        $form = $this->createForm(TaskByEmailType::class, $task)
            ->handleRequest($request);

        $this->denyAccessUnlessGranted('send', $task);

        if ($request->request->get('email')){
            $this->sendEmail->taskByEmail(
                $task->getTitle(),
                $task->getContent(),
                $request->request->get('email'),
                $this->getUser()->getEmail()
            );

            $this->addFlash(
                'success',
                'Votre tâche à bien été envoyer à : '.$request->request->get('email')
            );

            return $this->redirectToRoute('tasks');
        }

        return $this->render('task/task_byEmail.html.twig', [
            'title' => 'Partager vers mes contacts',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(path="/task/pin/{id}", name="task_pin", methods={"GET"})
     *
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function pin($id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $taskPin = $this->repository->find($id);

        if (!$taskPin){
            throw new \Exception('no task with this ID');
        }

        $this->cache->deleteItem('tasks');

        if ($taskPin->getPin() == true){
            $taskPin->notPin();

            $this->manager->flush();

            $this->addFlash(
                'success',
                'La tâche n\'est plus épinglée'
            );

            return $this->redirectToRoute('tasks');
        }

        $taskPin->pin();

        $this->manager->flush();

        $this->addFlash(
            'success',
            'Tâche épinglée'
        );

        return $this->redirectToRoute('tasks');
    }
}
