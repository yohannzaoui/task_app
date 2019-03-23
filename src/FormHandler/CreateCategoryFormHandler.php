<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 22/03/19
 * Time: 13:48
 */

namespace App\FormHandler;


use App\Entity\Category;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class CreateCategoryFormHandler
 *
 * @package App\FormHandler
 */
class CreateCategoryFormHandler
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $manager;

    private $security;

    /**
     * CreateCategoryFormHandler constructor.
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
     * @param \App\Entity\Category                  $category
     *
     * @return bool
     */
    public function handle(FormInterface $form, Category $category)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $category->setAuthor($this->security->getUser());
            $this->manager->persist($category);
            $this->manager->flush();

            return true;
        }
        return false;
    }
}