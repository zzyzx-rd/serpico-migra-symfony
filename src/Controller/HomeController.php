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
        if(!isset($_COOKIE['cb'])){ 
            setcookie('cb', 0); 
            $validatedCookieBanner = 0;
        } else {
            $validatedCookieBanner = $_COOKIE['cb'];
        }
        

        return $this->render('landing.html.twig',
            [
                'controller_name' => 'SecurityController',
                'workerFirmActDummies' => $workerFirmsActDummies,
                'validatedCookieBanner' => $validatedCookieBanner,
            ]);
    }

    /**
     * @Route("pricing", name="pricing")
     */
    public function pricing(): Response
    {
        if(!isset($_COOKIE['cb'])){ 
            setcookie('cb', 0); 
            $validatedCookieBanner = 0;
        } else {
            $validatedCookieBanner = $_COOKIE['cb'];
        }

        return $this->render('pricing.html.twig',
            [
                'validatedCookieBanner' => $validatedCookieBanner,
            ]);
    }

    /**
     * @Route("/terms/conditions", name="terms_conditions")
     */
    public function terms_index()
    {

        if(!isset($_COOKIE['cb'])){ 
            setcookie('cb', 0); 
            $validatedCookieBanner = 0;
        } else {
            $validatedCookieBanner = $_COOKIE['cb'];
        }

        return $this->render('terms_conditions.html.twig', [
            'validatedCookieBanner' => $validatedCookieBanner,
        ]);
    }

    /**
     * @Route("/terms/conditions/cookies", name="cookie_policy")
     */
    public function pageInfoCookie()
    {
        if(!isset($_COOKIE['cb'])){ 
            setcookie('cb', 0); 
            $validatedCookieBanner = 0;
        } else {
            $validatedCookieBanner = $_COOKIE['cb'];
        }

        return $this->render('cookie_policy.html.twig', [
            'validatedCookieBanner' => $validatedCookieBanner,
        ]);
    }

}