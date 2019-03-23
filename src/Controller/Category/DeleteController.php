<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 23/03/19
 * Time: 10:07
 */

namespace App\Controller\Category;

use App\Entity\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class DeleteController
 *
 * @package App\Controller\Category
 */
class DeleteController extends AbstractController
{
    /**
     * @Route(path="/category/delete/{id}", name="category_delete", methods={"DELETE"})
     *
     * @param \Symfony\Component\HttpFoundation\Request  $request
     * @param \App\Entity\Category                       $category
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(Request $request, Category $category, ObjectManager $manager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $manager->remove($category);
            $manager->flush();
        }
        return $this->redirectToRoute('category_index');
    }
}