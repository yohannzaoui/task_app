<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 22/03/19
 * Time: 08:28
 */

namespace App\Controller\Task;

use App\Entity\Task;
use App\Event\FileRemoverEvent;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DeleteController
 *
 * @package App\Controller\Task
 */
class DeleteController extends AbstractController
{
    /**
     * @Route(path="/task/delete/{id}", name="delete_task", methods={"GET"})
     *
     * @param                                                             $id
     * @param \Doctrine\Common\Persistence\ObjectManager                  $manager
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function delete($id, ObjectManager $manager, EventDispatcherInterface $eventDispatcher): Response
    {
        $task = $this->getDoctrine()
            ->getRepository(Task::class)
            ->find($id);

        if (!$task){
            throw new \Exception('Pas de tâche avec cet ID ');
        }

        $this->denyAccessUnlessGranted('access', $task);

        $eventDispatcher->dispatch(
            FileRemoverEvent::NAME,
            new FileRemoverEvent($task->getImage()
            )
        );
        $task->setImage(null);

        $manager->remove($task);
        $manager->flush();

        $this->addFlash(
            'success',
            'Tâche supprimer'
        );

        return $this->redirectToRoute('tasks');
    }
}