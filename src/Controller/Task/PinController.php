<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 22/03/19
 * Time: 08:47
 */

namespace App\Controller\Task;

use App\Entity\Task;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class PinController
 *
 * @package App\Controller\Task
 */
class PinController extends AbstractController
{

    /**
     * @Route(path="/task/pin/{id}", name="task_pin", methods={"GET"})
     *
     * @param                                            $id
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function pin(string $id, ObjectManager $manager): Response
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

            $manager->flush();

            $this->addFlash(
                'success',
                'La tâche n\'est plus épinglée'
            );

            return $this->redirectToRoute('tasks');
        }

        $taskPin->pin();

        $manager->flush();

        $this->addFlash(
            'success',

            'Tâche épinglée'
        );

        return $this->redirectToRoute('tasks');
    }
}