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
use App\FormHandler\EditTaskFormHandler;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class EditController
 *
 * @package App\Controller\Task
 */
class EditController extends AbstractController
{
    /**
     * @Route(path="/task/edit/{id}", name="edit_task", methods={"GET", "POST"})
     *
     * @param                                                $id
     * @param \Symfony\Component\HttpFoundation\Request      $request
     * @param \App\FormHandler\EditTaskFormHandler           $handler
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function edit($id, Request $request, EditTaskFormHandler $handler): Response
    {
        $task = $this->getDoctrine()
            ->getRepository(Task::class)
            ->find($id);

        if (!$task){
            throw new \Exception('Pas de tâche avec cet ID');
        }

        $form = $this->createForm(EditTaskType::class, $task)
            ->handleRequest($request);

        $this->denyAccessUnlessGranted('access', $task);


        if ($handler->handle($form, $task)){

            return $this->redirectToRoute('show_task', ['id' => $task->getId()]);
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'title' => 'Modifier la tâche'
        ]);
    }
}