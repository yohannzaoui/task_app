<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 23/03/19
 * Time: 09:50
 */

namespace App\Controller\Category;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Component\Routing\Annotation\Route;
use App\FormHandler\EditCategoryFormHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EditCategoryController extends AbstractController
{
    /**
     * @Route(path="/category/{id}/edit", name="category_edit", methods={"GET","POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\Category                      $category
     * @param \App\FormHandler\EditCategoryFormHandler  $handler
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, Category $category, EditCategoryFormHandler $handler): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($handler->handle($form)) {
            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'title' => 'Modifier la catégorie',
            'form' => $form->createView(),
        ]);
    }
}