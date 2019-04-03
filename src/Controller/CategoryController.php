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
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Handler\EditCategoryFormHandler;
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
     * @var \Psr\Cache\CacheItemPoolInterface
     */
    private $pool;

    /**
     * CategoryController constructor.
     *
     * @param \Psr\Cache\CacheItemPoolInterface $pool
     */
    public function __construct(CacheItemPoolInterface $pool)
    {
        $this->pool = $pool;
    }

    /**
     * @Route(path="/category", name="category_index", methods={"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findBy(['author' => $this->getUser()]);

        $item = $this->pool->getItem('cat');

        if (!$item->isHit()){
            $item->expiresAfter(3600);
            $this->pool->save($item->set($categories));
        }

        $categories = $item->get();

        return $this->render('category/index.html.twig', [
            'categories' => $categories
        ]);
    }


    /**
     * @Route(path="/category/new", name="category_new", methods={"GET","POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request   $request
     * @param \App\Form\Handler\CreateCategoryFormHandler $handler
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function create(Request $request, CreateCategoryFormHandler $handler): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category)
            ->handleRequest($request);

        if ($handler->handle($form, $category)) {
            $this->pool->deleteItem('cat');

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
     * @param \App\Form\Handler\EditCategoryFormHandler $handler
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function edit(Request $request, Category $category, EditCategoryFormHandler $handler): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');


        $form = $this->createForm(CategoryType::class, $category)
            ->handleRequest($request);

        if ($handler->handle($form)) {
            $this->pool->deleteItem('cat');

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
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function delete(Request $request, Category $category, ObjectManager $manager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $this->pool->deleteItem('cat');

            $manager->remove($category);
            $manager->flush();
        }
        return $this->redirectToRoute('category_index');
    }
}