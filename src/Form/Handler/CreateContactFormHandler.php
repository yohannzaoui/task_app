<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 23/03/19
 * Time: 10:42
 */

namespace App\Form\Handler;


use App\Entity\Contact;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class CreateContactFormHandler
 *
 * @package App\FormHandler
 */
class CreateContactFormHandler
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $manager;

    /**
     * @var \Symfony\Component\Security\Core\Security
     */
    private $security;

    /**
     * CreateContactFormHandler constructor.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     * @param \Symfony\Component\Security\Core\Security  $security
     */
    public function __construct(ObjectManager $manager, Security $security)
    {
        $this->manager = $manager;
        $this->security = $security;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \App\Entity\Contact                   $contact
     *
     * @return bool
     */
    public function handle(FormInterface $form, Contact $contact)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setUser($this->security->getUser());

            $this->manager->persist($contact);
            $this->manager->flush();

            return true;
        }
        return false;
    }
}