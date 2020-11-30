<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class ErrorController extends MasterController
{

    /**
     * @Route("/error/error/error", name="error_testt")
     */
    public function error()
    {



        return $this->render("layout.html.twig");
    }
}
