<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 28/03/19
 * Time: 16:09
 */

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Form\Handler\CreateCategoryFormHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Handler\EditCategoryFormHandler;
use App\Repository\CategoryRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class CategoryController
 *
 * @package App\Controller
 */
class CategoryController extends AbstractController
{
    /**
     * @Route(path="/category", name="category_index", methods={"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findBy(['author' => $this->getUser()]);

        return $this->render('category/index.html.twig', [
            'categories' => $categories
        ]);
    }


    /**
     * @Route(path="/category/new", name="category_new", methods={"GET","POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request  $request
     * @param \App\Form\Handler\CreateCategoryFormHandler $handler
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request, CreateCategoryFormHandler $handler): Response
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
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route(path="/category/{id}/edit", name="category_edit", methods={"GET","POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\Category                      $category
     * @param \App\Form\Handler\EditCategoryFormHandler  $handler
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(
        Request $request,
        Category $category,
        EditCategoryFormHandler $handler
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $form = $this->createForm(CategoryType::class, $category)
            ->handleRequest($request);

        if ($handler->handle($form)) {
            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route(path="/category/delete/{id}", name="category_delete", methods={"DELETE"})
     *
     * @param \Symfony\Component\HttpFoundation\Request  $request
     * @param \App\Entity\Category                       $category
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(
        Request $request,
        Category $category,
        ObjectManager $manager
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $manager->remove($category);
            $manager->flush();
        }
        return $this->redirectToRoute('category_index');
    }
}