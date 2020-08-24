<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends MasterController
{
    /**
     * @Route("/login", name="login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $lastUserName = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render('login.html.twig', [
            'error' => $error,
            'lastUserName' => $lastUserName,
        ]);
    }

    //Logs current user
//    public function loginAction(Request $request, Application $app)
//    {
//
//        if ($currentUser) {
//            return $this->redirectToRoute('home');
//        }
//
//        $formFactory = $app['form.factory'];
//        $csrf_token = $app['csrf.token_manager']->getToken('token_id');
//        $contact = new Contact;
//        $contactForm = $formFactory->create(
//            ContactForm::class,
//            $contact,
//            [ 'standalone' => true ]
//        )->handleRequest($request);
//
//        return $app['twig']->render('landing.html.twig',
//            [
//                'csrf_token' => $csrf_token,
//                'error' => $app['security.last_error']($request),
//                'last_username' => $app['session']->get('security.last_username'),
//                'contactForm' => $contactForm->createView(),
//                'request' => $request
//            ]
//        );
//    }
    /**
     * @Route("/signup", name="signup")
     */
    public function signup(){
        //TOdO
    }
}