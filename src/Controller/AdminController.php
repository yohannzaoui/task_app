<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 28/03/19
 * Time: 15:52
 */

namespace App\Controller;


use App\Entity\User;
use App\Event\FileRemoverEvent;
use Doctrine\Common\Persistence\ObjectManager;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController
 *
 * @package App\Controller
 */
class AdminController extends AbstractController
{
    /**
     * @Route(path="/user", name="user_index", methods={"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users
        ]);
    }


    /**
     * @Route(path="/user/delete", name="user_delete", methods={"GET"})
     *
     * @param \App\Entity\User                                            $user
     * @param \Doctrine\Common\Persistence\ObjectManager                  $manager
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(
        User $user,
        ObjectManager $manager,
        EventDispatcherInterface $eventDispatcher
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $eventDispatcher->dispatch(
            FileRemoverEvent::NAME,
            new FileRemoverEvent($user->getImage()
            )
        );
        $user->setImage(null);

        if ($this->getUser()->getId()) {
            $manager->remove($user);
            $manager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}