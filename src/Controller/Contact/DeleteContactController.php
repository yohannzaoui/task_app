<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 23/03/19
 * Time: 10:36
 */

namespace App\Controller\Contact;

use App\Entity\Contact;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class DeleteContactController
 *
 * @package App\Controller\Contact
 */
class DeleteContactController extends AbstractController
{
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