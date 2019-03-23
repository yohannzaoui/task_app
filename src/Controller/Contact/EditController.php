<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 23/03/19
 * Time: 10:21
 */

namespace App\Controller\Contact;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\FormHandler\EditContactFormHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class EditController
 *
 * @package App\Controller\Contact
 */
class EditController extends AbstractController
{
    /**
     * @Route(path="/contact/{id}/edit", name="contact_edit", methods={"GET","POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\Contact                       $contact
     * @param \App\FormHandler\EditContactFormHandler   $handler
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, Contact $contact, EditContactFormHandler $handler): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $form = $this->createForm(ContactType::class, $contact)
            ->handleRequest($request);

        if ($handler->handle($form)) {
            return $this->redirectToRoute('contact_index');
        }

        return $this->render('contact/edit.html.twig', [
            'contact' => $contact,
            'title' => 'Modifier le contact',
            'form' => $form->createView(),
        ]);
    }
}