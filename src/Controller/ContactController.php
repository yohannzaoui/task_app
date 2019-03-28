<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 28/03/19
 * Time: 16:16
 */

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\FormHandler\CreateContactFormHandler;
use App\FormHandler\EditContactFormHandler;
use Doctrine\Common\Persistence\ObjectManager;
use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class ContactController
 *
 * @package App\Controller
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


    /**
     * @Route(path="/contact/{id}/delete", name="contact_delete", methods={"DELETE"})
     *
     * @param \Symfony\Component\HttpFoundation\Request  $request
     * @param \App\Entity\Contact                        $contact
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(Request $request, Contact $contact, ObjectManager $manager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if ($this->isCsrfTokenValid('delete'.$contact->getId(), $request->request->get('_token'))) {
            $manager->remove($contact);
            $manager->flush();
        }
        return $this->redirectToRoute('contact_index');
    }
}