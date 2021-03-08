<?php


namespace App\Controller;


use App\Entity\Organization;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

     /**
     * @Route("/organizations/plan/check", name="checkPlans")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function checkOrganizationPlan(): Response
    {
        if(!$this->user){
            return new JsonResponse(['msg'=>'error'],500);
        }
        $em = $this->em;
        $now = new DateTime();
        $org = $this->org;
        if($org->getLastCheckedPlan()->getTimestamp() >= 24 * 60 * 60){
            $response = ['updated' => 'y'];
            $org->setLastCheckedPlan($now);
            if(!$org->getStripeCusId() && $org->getPlan() != 3 && $now->getTimestamp() > $org->getExpired()->getTimestamp()){
                $response['la'] = 'y';
            }
            $em->flush();
            return new JsonResponse($response);
        } else {
            return new JsonResponse(['updated' => 'ntu']);
        }
    }
}
