<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 22/03/19
 * Time: 13:44
 */

namespace App\Controller\Category;

use App\Entity\Category;
use App\Form\CategoryType;
use App\FormHandler\CreateCategoryFormHandler;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class CreateCategoryController
 *
 * @package App\Controller\Category
 */
class CreateCategoryController extends AbstractController
{
    /**
     * @Route(path="/category/new", name="category_new", methods={"GET","POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request  $request
     * @param \App\FormHandler\CreateCategoryFormHandler $handler
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request, CreateCategoryFormHandler $handler): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category)
            ->handleRequest($request);

        if ($handler->handle($form, $category)) {

            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/new.html.twig', [
            'category' => $category,
            'title' => 'Ajouter une catégorie',
            'form' => $form->createView(),
        ]);
    }
}