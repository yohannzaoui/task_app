<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 23/03/19
 * Time: 10:22
 */

namespace App\Form\Handler;


use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormInterface;

/**
 * Class EditContactFormHandler
 *
 * @package App\FormHandler
 */
class EditContactFormHandler
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $manager;

    /**
     * EditContactFormHandler constructor.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return bool
     */
    public function handle(FormInterface $form)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->flush();

            return true;
        }
        return false;
    }
}