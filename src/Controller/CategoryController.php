<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoryController
 *
 * @package App\Controller
 */
class CategoryController extends AbstractController
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $manager;

    /**
     * CategoryController constructor.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager        $manager
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route(path="/category", name="category_index", methods={"GET"})
     *
     * @param \App\Repository\CategoryRepository $repository
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(CategoryRepository $repository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('category/index.html.twig', [
            'categories' => $repository->findBy([
                'author' => $this->getUser()
            ]),
            'title' => 'Mes catégories'
        ]);
    }

    /**
     * @Route(path="/category/new", name="category_new", methods={"GET","POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setAuthor($this->getUser());
            $this->manager->persist($category);
            $this->manager->flush();

            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/new.html.twig', [
            'category' => $category,
            'title' => 'Ajouter une catégorie',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(path="/category/{id}/edit", name="category_edit", methods={"GET","POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\Category                      $category
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, Category $category): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->flush();

            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'title' => 'Modifier la catégorie',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(path="/category/delete/{id}", name="category_delete", methods={"DELETE"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\Category                      $category
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(Request $request, Category $category): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $this->manager->remove($category);
            $this->manager->flush();
        }

        return $this->redirectToRoute('category_index');
    }
}
