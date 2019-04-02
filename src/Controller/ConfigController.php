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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('config.html.twig');
    }
}