<?php

namespace App\Controller;

use App\Entity\Task;
use App\Event\TaskByEmailEvent;
use App\Event\TaskToMyEmailEvent;
use App\Form\EditTaskType;
use App\Form\TaskType;
use App\Service\FileUploader;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Event\FileRemoverEvent;

/**
 * Class TaskController
 *
 * @package App\Controller
 */
class TaskController extends AbstractController
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
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * TaskController constructor.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager                  $manager
     * @param \App\Service\FileUploader                                   $fileUploader
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        ObjectManager $manager,
        FileUploader $fileUploader,
        EventDispatcherInterface $eventDispatcher
    ){
        $this->manager = $manager;
        $this->fileUploader = $fileUploader;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @Route(path="/", name="tasks", methods={"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
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

    /**
     * @Route(path="/task/show/{id}", name="show_task", methods={"GET", "POST"})
     *
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function show($id)
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

    /**
     * @Route(path="/task/create", name="create_task", methods={"GET", "POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function create(Request $request): Response
    {
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            if ($form->getData()->getFile()){
                $fileName = $this->fileUploader->upload($form->getData()->getFile());
                $task->setImage($fileName);
            }

            $task->setAuthor($this->getUser());

            $this->manager->persist($task);
            $this->manager->flush();

            $this->addFlash('success',
                'Tâche ajouter'
            );

            return $this->redirectToRoute('show_task', ['id' => $task->getId()]);
        }
        return $this->render('task/create.html.twig', [
            'form' =>$form->createView(),
            'title' => 'Créer une tâche'
        ]);
    }

    /**
     * @Route(path="/task/edit/{id}", name="edit_task", methods={"GET", "POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param                                           $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function edit(Request $request, $id): Response
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

    /**
     * @Route(path="/task/delete/image/{id}", name="delete_task_image", methods={"GET"})
     *
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteTaskImage($id)
    {
        $task = $this->getDoctrine()
            ->getRepository(Task::class)
            ->find($id);

        $this->denyAccessUnlessGranted('delete', $task);

        $this->eventDispatcher->dispatch(
            FileRemoverEvent::NAME,
            new FileRemoverEvent($task->getImage()
            )
        );

        $task->setImage(null);

        $this->manager->flush();

        return $this->redirectToRoute('show_task', ['id' => $task->getId()]);
    }

    /**
     * @Route(path="/task/delete/{id}", name="delete_task", methods={"GET"})
     *
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function delete($id): Response
    {
        $task = $this->getDoctrine()
            ->getRepository(Task::class)
            ->find($id);

        if (!$task){
            throw new \Exception('Pas de tâche avec cet ID ');
        }

        $this->denyAccessUnlessGranted('delete', $task);

        $this->manager->remove($task);
        $this->manager->flush();

        $this->addFlash(
            'success',
            'Tâche supprimer'
        );

        return $this->redirectToRoute('tasks');
    }

    /**
     * @Route(path="/task/done/{id}", name="done_task", methods={"GET"})
     *
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function done($id): Response
    {
        $task = $this->getDoctrine()
            ->getRepository(Task::class)
            ->find($id);

        if (!$task){
            throw new \Exception('Pas de tâche avec cet ID');
        }

        $this->denyAccessUnlessGranted('done', $task);

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

    /**
     * @Route(path="/send/task/myEmail/{id}", name="send_task_myEmail", methods={"GET"})
     *
     * @param                                                             $id
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function sendTaskToMyEmail($id, EventDispatcherInterface $eventDispatcher)
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

    /**
     * @Route(path="/send/task/byEmail", name="send_task_byEmail", methods={"GET", "POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function sendTaskByEmail(Request $request): Response
    {
        $task = $this->getDoctrine()
            ->getRepository(Task::class)
            ->find($request->request->get('task_id'));

        if (!$task){
            throw new \Exception('Pas de tâche avec cet ID');
        }

        if ($this->isCsrfTokenValid('email', $request->request->get('_csrf_token'))){
            $this->denyAccessUnlessGranted('send', $task);

            $this->eventDispatcher->dispatch(
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

    /**
     * @Route(path="/task/pin/{id}", name="task_pin", methods={"GET"})
     *
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function pin($id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $taskPin = $this->getDoctrine()
            ->getRepository(Task::class)
            ->find($id);

        if (!$taskPin){
            throw new \Exception('no task with this ID');
        }

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
