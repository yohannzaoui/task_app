<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 23/03/19
 * Time: 19:20
 */

namespace App\Controller\Admin;

use App\Entity\User;
use App\Event\FileRemoverEvent;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DeleteController
 *
 * @package App\Controller\Admin
 */
class DeleteController extends AbstractController
{
    /**
     * @Route(path="/user/{id}", name="user_delete", methods={"GET"})
     *
     * @param \App\Entity\User                                            $user
     * @param \Doctrine\Common\Persistence\ObjectManager                  $manager
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param                                                             $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(User $user, ObjectManager $manager, EventDispatcherInterface $eventDispatcher,$id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $eventDispatcher->dispatch(
            FileRemoverEvent::NAME,
            new FileRemoverEvent($user->getImage()
            )
        );
        $user->setImage(null);

        if ($id) {
            $manager->remove($user);
            $manager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}