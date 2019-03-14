<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ContactController
 *
 * @package App\Controller
 */
class ContactController extends AbstractController
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $manager;

    /**
     * ContactController constructor.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

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
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setUser($this->getUser());

            $this->manager->persist($contact);
            $this->manager->flush();

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
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, Contact $contact): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $form = $this->createForm(ContactType::class, $contact)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->flush();

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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\Contact                       $contact
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(Request $request, Contact $contact): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if ($this->isCsrfTokenValid('delete'.$contact->getId(), $request->request->get('_token'))) {
            $this->manager->remove($contact);
            $this->manager->flush();
        }

        return $this->redirectToRoute('contact_index');
    }
}
