<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class LocaleController
 *
 * @package App\Controller
 */
class LocaleController extends AbstractController
{
    /**
     * @Route(path="/lang", name="lang", methods={"GET"})
     *
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $title = $translator->trans('Language of the application');

        return $this->render('lang.html.twig', [
            'title' => $title
        ]);
    }


    /**
     * @Route(path="/lang/{locale}", name="lang_locale", methods={"GET"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param                                           $locale
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function locale(Request $request, $locale): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if ($request->attributes->get('locale')){
            $request->getSession()->set('_locale', $locale);
            return $this->redirectToRoute('tasks');
        }
    }
}