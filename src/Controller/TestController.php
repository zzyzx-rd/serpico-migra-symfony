<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{

    /**
     * @Route("/test",name="test" )
     * @return Response
     */
    public function test(){
        return $this->render('test/test.html.twig');
    }
    /**
     * @Route("/about", name="about")
     */
    public function about_index()
    {
        return $this->render('about.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
    /**
     * @Route("/solution", name="solution")
     */
    public function solution_index()
    {
        return $this->render('use_cases.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
    /**
     * @Route("/terms", name="terms_conditions")
     */
    public function terms_index()
    {
        return $this->render('terms_conditions.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
