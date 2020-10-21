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
        
        $workerFirmsArrayRespContent = $this->forward('App\Controller\OrganizationController::getDummyClientsAndActNames')->getContent();
        $workerFirmsActDummies = json_decode($workerFirmsArrayRespContent,true)['dummyElmts'];

        return $this->render('landing.html.twig',
            [
                'controller_name' => 'SecurityController',
                'workerFirmActDummies' => $workerFirmsActDummies,
            ]);
    }

    /**
     * @Route("pricing", name="pricing")
     */
    public function pricing(): Response
    {
        
        return $this->render('pricing.html.twig',
            [

            ]);
    }

}