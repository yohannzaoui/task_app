<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 23/03/19
 * Time: 10:19
 */

namespace App\Controller\Contact;

use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class ContactController
 *
 * @package App\Controller\Contact
 */
class ContactController extends AbstractController
{
    /**
     * @Route(path="/contact", name="contact_index", methods={"GET"})
     *
     * @param \App\Repository\ContactRepository $repository
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(ContactRepository $repository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('contact/index.html.twig', [
            'contacts' => $repository->findBy([
                'user' => $this->getUser()
            ]),
            'title' => 'Mes contacts'
        ]);
    }
}