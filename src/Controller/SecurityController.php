<?php


namespace App\Controller;


use App\Entity\Organization;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
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
        $this->checkUpAccount();
        return $this->render('login.html.twig', [
            'error' => $error,
            'lastUserName' => $lastUserName,
        ]);
    }

    public function checkUpAccount(): void
    {
        $em = $this->em ;


        $account = new ArrayCollection($em->getRepository(Organization::class)->findAll());

        $account->filter(function(Organization $o){
            $now = new DateTime();
            return ($o->getInserted()->diff($now)->format('%d') > 21);
        });

        for($i=0;$i<sizeof($account);$i++){
           
            if($account[$i]->getCustomerId()==null && $account[$i]->getPlan() == 1){
            $account[$i]->setPlan(3);
        }}
    }
    //Logs current user
//    public function loginAction(Request $request)
//    {
//
//        if ($currentUser) {
//            return $this->redirectToRoute('home');
//        }
//
//        
//        $csrf_token = $app['csrf.token_manager']->getToken('token_id');
//        $contact = new Contact;
//        $contactForm = $this->createForm(
//            ContactForm::class,
//            $contact,
//            [ 'standalone' => true ]
//        )->handleRequest($request);
//
//        return $this->render('landing.html.twig',
//            [
//                'csrf_token' => $csrf_token,
//                'error' => $app['security.last_error']($request),
//                'last_username' => $app['session']->get('security.last_username'),
//                'contactForm' => $contactForm->createView(),
//                'request' => $request
//            ]
//        );
//    }

}
