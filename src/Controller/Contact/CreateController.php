<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 23/03/19
 * Time: 10:39
 */

namespace App\Controller\Contact;

use App\Entity\Contact;
use App\Form\ContactType;
use App\FormHandler\CreateContactFormHandler;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class CreateController
 *
 * @package App\Controller\Contact
 */
class CreateController extends AbstractController
{
    /**
     * @Route(path="/contact/new", name="contact_new", methods={"GET","POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\FormHandler\CreateContactFormHandler $handler
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function create(Request $request, CreateContactFormHandler $handler): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact)
            ->handleRequest($request);

        if ($handler->handle($form, $contact)) {
            return $this->redirectToRoute('contact_index');
        }
        return $this->render('contact/new.html.twig', [
            'contact' => $contact,
            'title' => 'Ajouter un contact',
            'form' => $form->createView(),
        ]);
    }
}