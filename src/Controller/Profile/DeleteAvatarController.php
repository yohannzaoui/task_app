<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 23/03/19
 * Time: 21:11
 */

namespace App\Controller\Profile;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Event\FileRemoverEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class DeleteAvatarController
 *
 * @package App\Controller\Profile
 */
class DeleteAvatarController extends AbstractController
{
    /**
     * @Route(path="/delete/avatar/", name="delete_avatar", methods={"GET"})
     *
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Doctrine\Common\Persistence\ObjectManager                  $manager
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAvatar(EventDispatcherInterface $eventDispatcher, ObjectManager $manager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $eventDispatcher->dispatch(
            FileRemoverEvent::NAME,
            new FileRemoverEvent($this->getUser()->getImage()
            )
        );

        $this->getUser()->setImage(null);

        $manager->flush();

        return $this->redirectToRoute('profile');
    }
}