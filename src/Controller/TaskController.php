<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\EditTaskType;
use App\Form\TaskType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TaskController
 *
 * @package App\Controller
 */
class TaskController extends AbstractController
{
    /**
     * @Route(path="/", name="tasks", methods={"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $tasks = $this->getDoctrine()
            ->getRepository(Task::class)
            ->findBy([
                'done'=> false,
                'author' => $this->getUser()
            ]);

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }


    /**
     * @Route(path="/task/create", name="create_task", methods={"GET", "POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request  $request
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function create(Request $request, ObjectManager $manager)
    {
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $task->setAuthor($this->getUser());

            $manager->persist($task);
            $manager->flush();

            return $this->redirectToRoute('task');
        }
        return $this->render('task/create.html.twig', [
            'form' =>$form->createView()
        ]);
    }

    /**
     * @Route(path="/task/edit/{id}", name="edit_task", methods={"GET", "POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request  $request
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     * @param                                            $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function edit(Request $request, ObjectManager $manager, $id)
    {
        $task = $this->getDoctrine()
            ->getRepository(Task::class)
            ->find($id);

        if (!$task){
            throw new \Exception('no task with this ID');
        }

        $form = $this->createForm(EditTaskType::class, $task)
            ->handleRequest($request);

        $this->denyAccessUnlessGranted('edit', $task);

        if ($form->isSubmitted() && $form->isValid()){
            $task->updateDate();

            $manager->flush();

            return $this->redirectToRoute('tasks');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(path="/task/delete/{id}", name="delete_task", methods={"GET"})
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     * @param                                            $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function delete(ObjectManager $manager, $id)
    {
        $task = $this->getDoctrine()
            ->getRepository(Task::class)
            ->find($id);

        if (!$task){
            throw new \Exception('no task with this ID');
        }

        $this->denyAccessUnlessGranted('delete', $task);

        $manager->remove($task);
        $manager->flush();

        return $this->redirectToRoute('tasks');
    }

    /**
     * @Route(path="/task/done/{id}", name="done_task", methods={"GET"})
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     * @param                                            $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function done(ObjectManager $manager, $id)
    {
        $task = $this->getDoctrine()
            ->getRepository(Task::class)
            ->find($id);

        if (!$task){
            throw new \Exception('no task with this ID');
        }

        $this->denyAccessUnlessGranted('done', $task);

        if ($task->isDone() == true){
            $task->notDone();

            $manager->flush();

            return $this->redirectToRoute('tasks');
        }

        $task->done();
        $task->doneDate();

        $manager->flush();

        return $this->redirectToRoute('view_done_task');
    }

    /**
     * @Route(path="/task/done", name="view_done_task", methods={"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function doneTask()
    {
        $tasks = $this->getDoctrine()
            ->getRepository(Task::class)
            ->findBy([
                'done'=> true,
                'author' => $this->getUser()
            ]);

        return $this->render('task/done.html.twig', [
            'tasks' => $tasks,
        ]);
    }
}
