<?php
/**
 * Created by PhpStorm.
 * User: yohann
 * Date: 09/03/19
 * Time: 15:23
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ConfigController
 *
 * @package App\Controller
 */
class ConfigController extends AbstractController
{
    /**
     * @Route(path="/config", name="config", methods={"GET"})
     *
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $title = $translator->trans('Parameters');

        return $this->render('config.html.twig', ['title' => $title]);
    }
}