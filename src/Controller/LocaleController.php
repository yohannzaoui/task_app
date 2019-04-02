<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class LocaleController
 *
 * @package App\Controller
 */
class LocaleController extends AbstractController
{
    /**
     * @Route(path="/lang", name="lang")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(): Response
    {
        return $this->render('lang.html.twig');
    }


    /**
     * @Route(path="/lang/{locale}", name="lang_locale", methods={"GET"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param                                           $locale
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function locale(Request $request, $locale): Response
    {
        if ($request->attributes->get('locale')){
            $request->getSession()->set('_locale', $locale);
            return $this->redirectToRoute('tasks');
        }
    }
}