<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends MasterController
{
    /**
     * @Route("/", name="home_welcome")
     */
    public function home(): Response
    {

        return $this->render('landing.html.twig',
            ['controller_name' => 'SecurityController']);
    }
}