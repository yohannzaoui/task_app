<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 22/03/19
 * Time: 08:34
 */

namespace App\Controller\Task;

use App\Entity\Task;
use App\Service\FileUploader;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class CreateTaskController
 *
 * @package App\Controller\Task
 */
class CreateTaskController extends AbstractController
{
    /**
     * @Route(path="/task/create", name="create_task", methods={"GET", "POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request  $request
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     * @param \App\Service\FileUploader                  $fileUploader
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function create(Request $request, ObjectManager $manager, FileUploader $fileUploader): Response
    {
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            if ($form->getData()->getFile()){
                $fileName = $fileUploader->upload($form->getData()->getFile());
                $task->setImage($fileName);
            }

            $task->setAuthor($this->getUser());

            $manager->persist($task);
            $manager->flush();

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
}