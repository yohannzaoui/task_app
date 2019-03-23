<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 22/03/19
 * Time: 08:50
 */

namespace App\Controller\Task;

use App\Entity\Task;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Event\FileRemoverEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class DeleteImageController
 *
 * @package App\Controller\Task
 */
class DeleteImageController extends AbstractController
{
    /**
     * @Route(path="/task/delete/image/{id}", name="delete_task_image", methods={"GET"})
     *
     * @param                                                             $id
     * @param \Doctrine\Common\Persistence\ObjectManager                  $manager
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteImage($id, ObjectManager $manager, EventDispatcherInterface $eventDispatcher): Response
    {
        $task = $this->getDoctrine()
            ->getRepository(Task::class)
            ->find($id);

        $this->denyAccessUnlessGranted('access', $task);

        $eventDispatcher->dispatch(
            FileRemoverEvent::NAME,
            new FileRemoverEvent($task->getImage()
            )
        );

        $task->setImage(null);

        $manager->flush();

        return $this->redirectToRoute('show_task', ['id' => $task->getId()]);
    }
}