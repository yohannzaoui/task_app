<?php

namespace App\Controller;

use Exception;
use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class SearchController
 *
 * @package App\Controller
 */
class SearchController extends AbstractController
{
    /**
     * @Route(path="/search", name="search", methods={"GET", "POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request          $request
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function search(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if ($this->isCsrfTokenValid('search', $request->request->get('token'))){
            $results = $this->getDoctrine()
                ->getRepository(Task::class)
                ->search($request->request->get('search'));

            $title = $translator->trans('Search result');

            return $this->render('search/index.html.twig', [
                'results' => $results,
                'title' => $title
            ]);
        }
        throw new Exception('Error: invalid CSRF token');
    }
}
