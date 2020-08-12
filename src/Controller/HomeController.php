<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="firstPage")
     */
    public function home(){

        return $this->render('landing.html.twig',
            ['controller_name' => 'SecurityController']);
    }
}