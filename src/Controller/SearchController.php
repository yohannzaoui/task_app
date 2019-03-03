<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SearchController
 *
 * @package App\Controller
 */
class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search", methods={"GET", "POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function search(Request $request)
    {
        if ($this->isCsrfTokenValid('search', $request->request->get('token'))){
            $results = $this->getDoctrine()
                ->getRepository(Task::class)
                ->search($request->request->get('search'));

            return $this->render('search/index.html.twig', [
                'results' => $results,
            ]);
        }
        throw new \Exception('Error: invalid CSRF token');
    }
}
