<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 22/03/19
 * Time: 13:42
 */

namespace App\Controller\Category;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class CategoriesController
 *
 * @package App\Controller\Category
 */
class CategoriesController extends AbstractController
{
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
}