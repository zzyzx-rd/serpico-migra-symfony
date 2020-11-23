<?php
namespace App\Controller;

use App\Entity\OrganizationPaymentMethod;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use App\Form\AddOrganizationForm;
use App\Form\AddClientForm;
use App\Form\DelegateActivityForm;
use App\Form\RequestActivityForm;
use App\Form\UpdateWorkerFirmForm;
use App\Form\UpdateWorkerIndividualForm;
use App\Form\SendMailProspectForm;
use App\Form\AddUserForm;
use App\Form\AddDepartmentForm;
use App\Form\AddProcessForm;
use App\Form\AddWeightForm;
use App\Form\SendMailForm;
use App\Form\SearchWorkerForm;
use App\Form\UpdateOrganizationForm;
use App\Form\ValidateFirmForm;
use App\Form\ManageProcessForm;
use App\Form\ValidateMassFirmForm;
use App\Form\ValidateMailForm;
use App\Form\ValidateMassMailForm;
use App\Form\Type\UserType;
use App\Form\Type\ClientUserType;
use App\Form\Type\OrganizationElementType;
use App\Entity\Participation;
use App\Entity\Criterion;
use App\Entity\OrganizationUserOption;
use App\Entity\Process;
use App\Entity\Team;
use App\Entity\Weight;
use App\Entity\WorkerExperience;
use App\Entity\WorkerFirm;
use App\Entity\WorkerFirmCompetency;
use App\Entity\WorkerFirmSector;
use App\Entity\WorkerIndividual;
use App\Entity\Country;
use App\Entity\State;
use App\Entity\City;
use Stripe;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Entity\ExternalUser;
use App\Entity\Organization;
use App\Entity\Client;
use App\Entity\OptionName;
use App\Entity\Department;
use App\Entity\Position;
use App\Entity\Activity;
use App\Entity\CriterionGroup;
use App\Entity\CriterionName;
use App\Entity\InstitutionProcess;
use App\Entity\Mail;
use DateTime;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Validator\Constraints\NotBlank;



class SettingsController extends MasterController
{


    public static function getClientLangague(){
        $langs = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
        return substr($langs[0], 0, 2);
    }
 
    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     * @Route("/settings/testingmails", name="displayTestingMails")
     */
    public function displayTestingMails(Request $request)
    {


        $em = $this->em;
        $repoU = $em->getRepository(User::class);
        
        $sendMailForm = $this->createForm(SendMailForm::class, null, ['standalone' => true]);
        $sendMailForm->handleRequest($request);
        //$user = $em->getRepository(User::class)->findOneById(9);
        $actionType = $sendMailForm->get('emailType')->getData();
        $recipients = [];
        $settings = [];
        $settings['locale'] = $sendMailForm->get('lang')->getData();

        return $this->render('mail_testing.html.twig',
            [
                'form' => $sendMailForm->createView(),
            ]);

    }

    /**
     * @param Request $request
     * @param Application $app
     * @return string|JsonResponse
     * @Route("/settings/testingmails", name="sendTestingMails")
     */
    public function sendTestingMails(Request $request)
    {


        $em = $this->em;
        $repoU = $em->getRepository(User::class);
        
        $sendMailForm = $this->createForm(SendMailForm::class, null, ['standalone' => true]);
        $sendMailForm->handleRequest($request);
        //$user = $em->getRepository(User::class)->findOneById(9);

        if ($sendMailForm->isSubmitted()) {
            if ($sendMailForm->isValid()) {

                try {
                    $actionType = $sendMailForm->get('emailType')->getData();
                    $parameters = $sendMailForm->get('emailParameters')->getData();
                    $settings = [];
                    foreach($parameters as $parameter){
                        $settings[$parameter['parameterKey']] = $parameter['parameterValue'];
                    }
                    $recipients = [];
                    $settings['locale'] = $sendMailForm->get('lang')->getData();
                    $settings['locale'] = $sendMailForm->get('lang')->getData();

                    $recipient = $repoU->findOneByEmail($sendMailForm->get('emailAddress')->getData());
                    $recipients[] = $recipient;
                    $request->setLocale($sendMailForm->get('lang')->getData());

                    $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => $actionType]);
                    return new JsonResponse(['message' => "Success"],200);
                }
                catch (Exception $e){
                    return $e->getLine().' : '.$e->getMessage();
                }

            }
        }
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     * @Route("/settings/root/management", name="rootManagement")
     */
    public function rootManagementAction(Request $request){

        return $this->render('root_management.html.twig');

    }
   /**
     * @param Request $request
     * @return mixed
     * @Route("/organization/plan/manage", name="priceManagement")
     */
    public function PriceManagementAction(Request $request){
        $em = $this->em;
        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        $priceStandard = [5,6,7];
        $pricePrenium = [7,10,14];
        $org = $this->org;
        
        /*if(!$org->getCustomerId()){
            $mail = $org->getMasterUser() ? $org->getMasterUser()->getEmail() : "";
            $cust = Stripe\Customer::create([
                'email' => $mail,
            ]);
            $org->setCustomerId($cust->id);
        }*/

        $payacces = false;
        Stripe\Stripe::setApiKey('sk_test_51Hn5ftLU0XoF52vKQ1r5r1cONYas5XjLLZu6rFg2P69nntllHxLs3G0wyCxoOQNUgjgD5LwCoaYTkGQp1qVK3g3A00LfW1k4Ep');

        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }

        if($this->org->getPaymentUser() == $this->user || $this->org->getPaymentUser()== null){
            $payacces = true ;
        }


        $organization = $this->org;
        if($this->org->getCustomerId() != null) {
            $customer = $this->stripe->customers->retrieve(
                $this->org->getCustomerId()
            );
            $users = $this->org->getUsers();


            if ($customer->metadata->sub_id == null) {
                $val = 1;
                $start = " ";
                $pdf = " ";
            } else {
                $sub = $this->stripe->subscriptions->retrieve(
                    $customer->metadata->sub_id,
                    );
                $invoice = $this->stripe->invoices->all(['customer' => $this->org->getCustomerId()])->data[0];
                $invoice = $this->stripe->invoices->sendInvoice($invoice->id,
                    []
                );
                $pdf = $invoice->invoice_pdf;

                $start = date('m/d/Y', $sub->current_period_start);

                $val = $customer->metadata->quantity;
            }
            $pm = ($customer->invoice_settings->default_payment_method != null) ? true : false;
            if ($pm) {
                $payment_method = $this->stripe->paymentMethods->retrieve(
                    $customer->invoice_settings->default_payment_method
                );

                $month = $payment_method->card->exp_month;
                if ($month < 10) {
                    $montheend = "0" . (string)$month;
                } else {
                    $montheend = $month;
                }
                $dateend = (string)$payment_method->card->exp_year;
                $dateend = $montheend . "/" . $dateend[2] . $dateend[3];
                $last4 = $payment_method->card->last4;
                $name = $payment_method->billing_details->name;
                $brand = $payment_method->card->brand;
            } else {
                $dateend = "";
                $last4 = "";
                $name = "";
                $brand = "";
            }
            $subscription =$this->stripe->subscriptions->all(['customer' => $customer]);
            $sub=  $subscription->data ;
        } else {
            $val = 1;
            $start = " ";
            $pdf = " ";
            $dateend = "";
            $last4 = "";
            $name = "";
            $brand = "";
            $users = $this->org->getUsers();
            $sub= " ";
        }


        return $this->render( 'price_management.html.twig' , [

            'currentPlan' => $organization->getPlan(),
            'val' => $val,
            'custcard' => sizeof($this->org->getPaymentMethods()),
            'dateend'  => $dateend,
            'last4'  => $last4,
            'name' => $name,
            'brand' => $brand,
            'start' => $start,
            'payacces' => $payacces,
            'cards' => $this->org->getPaymentMethods(),
            'pdf'=>$pdf,
            'user'=>$users,
            'sub' => $sub
        ]);



    }
    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     * @Route("/stripe-webhook", name="stripWebook")
     */
    public function stripWebookManagementAction(Request $request){

        $logger = $this->get('logger');
        $event = $request->getParsedBody();
        $stripe = $this->stripe;

        // Parse the message body (and check the signature if possible)
        $webhookSecret = getenv('STRIPE_WEBHOOK_SECRET');
        if ($webhookSecret) {
            try {
                $event = $stripe->webhooks->constructEvent(
                    $request->getBody(),
                    $request->getHeaderLine('stripe-signature'),
                    $webhookSecret
                );

            } catch (Exception $e) {
                return new JsonResponse(['error' => $e->getMessage()  ], 403);
            }
        } else {
            $event = $request->getParsedBody();
        }
        $type = $event['type'];
        $object = $event['data']['object'];

        // Handle the event
        // Review important events for Billing webhooks
        // https://stripe.com/docs/billing/webhooks
        // Remove comment to see the various objects sent for this sample
        switch ($type) {
            case 'invoice.paid':
                // The status of the invoice will show up as paid. Store the status in your
                // database to reference when a user accesses your service to avoid hitting rate
                // limits.
                $logger->info('🔔  Webhook received! ' . $object);
                break;
            case 'invoice.payment_failed':
                // If the payment fails or the customer does not have a valid payment method,
                // an invoice.payment_failed event is sent, the subscription becomes past_due.
                // Use this webhook to notify your user that their payment has
                // failed and to retrieve new card details.
                $logger->info('🔔  Webhook received! ' . $object);
                break;
            case 'customer.subscription.deleted':
                // handle subscription cancelled automatically based
                // upon your subscription settings. Or if the user
                // cancels it.
                $logger->info('🔔  Webhook received! ' . $object);
                break;
            // ... handle other event types
            default:
                // Unhandled event type
        }

        return new JsonResponse(['status' => 'success' ], 200);


    }
    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     * @Route("/create-customer", name="stripWebook")
     */
    public function createCustomerManagementAction(Request $request){
        Stripe\Stripe::setApiKey('sk_test_51Hn5ftLU0XoF52vKQ1r5r1cONYas5XjLLZu6rFg2P69nntllHxLs3G0wyCxoOQNUgjgD5LwCoaYTkGQp1qVK3g3A00LfW1k4Ep');

        $body =  json_decode(file_get_contents('php://input'), true);
        $stripe = $this->stripe;

        $customer = \Stripe\Customer::create([
            'email' => $body['email'],
        ]);


        return new JsonResponse(['customer' => $customer ], 200);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     * @Route("/create-checkout-session", name="createSession")
     * @throws Stripe\Exception\ApiErrorException
     */
    public function createSessionManagementAction(Request $request){
        Stripe\Stripe::setApiKey('sk_test_51Hn5ftLU0XoF52vKQ1r5r1cONYas5XjLLZu6rFg2P69nntllHxLs3G0wyCxoOQNUgjgD5LwCoaYTkGQp1qVK3g3A00LfW1k4Ep');

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => 'price_1HZI0THmuPtR98gq67btxwid',
                'quantity' => 1 ,
            ]],
            'mode' => 'subscription',
            'success_url' => 'https://example.com/success',
            'cancel_url' => 'https://example.com/cancel'
        ]);

        return new JsonResponse([ 'id' => $session->id ], 200);
    }
    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     * @Route("/create-freesubscription", name="createFreeSubscription")
     */
    public function createFreeSubscriptionManagementAction(Request $request){

        $em = $this->em;
        $currentUser = $this->user;
        Stripe\Stripe::setApiKey('sk_test_51Hn5ftLU0XoF52vKQ1r5r1cONYas5XjLLZu6rFg2P69nntllHxLs3G0wyCxoOQNUgjgD5LwCoaYTkGQp1qVK3g3A00LfW1k4Ep');
        $body =  json_decode(file_get_contents('php://input'), true);
        $stripe = $this->stripe;

        $priceId = $body['priceId'];
        $customer = $this->org->getCustomerId();

        $payment_method = Stripe\PaymentMethod::create([
            'type' => 'card',
            'card' => [
                'number' => '4242424242424242',
                'exp_month' => 10,
                'exp_year' => 2021,
                'cvc' => '314',
            ],
        ]);

        $payment_method->attach([
            'customer' => $customer,
        ]);








        // Set the default payment method on the customer
        try { Stripe\Customer::update($body['customerId'], [
            'invoice_settings' => [
                'default_payment_method' => $payment_method->id
            ]
        ]);
        }
        catch (Exception $e) {

        }



        $subscription = Stripe\Subscription::create([
            'customer' => $customer,
            'items' => [
                [
                    'price' => $priceId,
                    'quantity' => $body['quantiy'],
                ],
            ],
            'expand' => ['latest_invoice.payment_intent'],
        ]);

        $p = Stripe\Product::retrieve(
            $subscription->items->data['0']->price->product
        );

        $p = $p->name;
        $org = $this->org;
        if ( $p == "abonnement standard" ) {
            $org->setPlan(2);

        } else {
            $org->setPlan(1);

        }
        $cust = Stripe\Customer::update(
            $customer,
            ['metadata' => ['sub_id' => $subscription->id,
                'quantity' => $body['quantity']]]
        );

        $org->setCustomerId($customer);

        $em->flush();



        return new JsonResponse($p, 200);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     * @Route("/create-subscription", name="createSubscription")
     * @throws Stripe\Exception\ApiErrorException
     */
    public function createSubscriptionManagementAction(Request $request){

        $em = $this->em;
        $repoO = $em->getRepository(OrganizationPaymentMethod::class);
        $paymentList = $this->org->getPaymentMethods();
        $currentUser = $this->user;
        Stripe\Stripe::setApiKey('sk_test_51Hn5ftLU0XoF52vKQ1r5r1cONYas5XjLLZu6rFg2P69nntllHxLs3G0wyCxoOQNUgjgD5LwCoaYTkGQp1qVK3g3A00LfW1k4Ep');
        $body =  json_decode(file_get_contents('php://input'), true);
        $stripe = $this->stripe;
       $period = ($body['period'] == "year") ? 10 : 1;
        $quantity =  (int) $body['quantity'];
        if ($quantity < 99) {
            $index = 0;
        } else if ($quantity < 249) {
            $index = 1;
        } else {
            $index = 2;
        }
        if($this->org->getPaymentUser()== null){
            $this->org->setPaymentUser($this->user);
        }

        if($body['product'] == "premium"){


            $mainProd = 'prod_IPHWR3lzuAWWsL';

        } else {


            $mainProd = 'prod_IPHWDsnMYL3UFB';

        }

        /*
        for($i =0 ; $i < sizeof($mainPrice);$i++){
            if($mainPrice[$i] == $body['priceId']) {
                $priceId = $mainPriceId[$i];
               
            }

        }*/
        $customer = $this->org->getCustomerId();
        if($customer == null){
            $mail = ($this->org->getMasterUser() == null) ? "" :$this->org->getMasterUser()->getEmail();
            $cust = Stripe\Customer::create([
                'email' => $mail
            ]);
            $customer = $cust->id;
            $this->org->setCustomerId($customer);
        }
        $cust = Stripe\Customer::retrieve($customer);
        $paymentm = ($body['paymentMethodId'] == " ") ? $paymentList[(int)$body['paymentMethodId']]->getPmid() : $body['paymentMethodId'];

        $priceResponse = $stripe->prices->create([
            'unit_amount' => $body['priceId']*100,
            'currency' => 'eur',
            'recurring' => ['interval' => $body['period']],
            'product' =>  $mainProd,
        ]);
        $priceId = $priceResponse->id;


        $payment_method = $this->stripe->paymentMethods->retrieve(
            $paymentm
        );

        $payment_method->attach([
            'customer' => $customer,
        ]);








        // Set the default payment method on the customer

            $pm = $cust->metadata->payment_method;
            $find = false;
            for($p = 1 ; $p< sizeof($paymentList);$p++) {

                if($paymentList[$p]->getPmId() == $paymentm){
                    $find= true;
                }

            }
            if(!$find){
                $payment_object = new OrganizationPaymentMethod();
                $payment_object->setPmId($paymentm);
                $this->org->addPaymentMethods($payment_object);
            }
            var_dump(sizeof($this->org->getPaymentMethods()));
          //  $payList = array_push($pm,$paymentm);


            $cust = Stripe\Customer::update($customer, [
            'invoice_settings' => [
                'default_payment_method' => $paymentm
            ],

        ]);
            try{
     if( $cust->metadata->sub_id != null) {

     } } catch (Exception $e){

            }



        $subscription = $this->stripe->subscriptions->create([
            'customer' => $customer,
            "collection_method" => "send_invoice",
            'items' => [
                [
                    'price' => $priceId,

                ],
            ],
            "days_until_due" => 1,
            'expand' => ['latest_invoice.payment_intent'],
        ]);
        $invoice =$this->stripe->invoices->all(['subscription' => $subscription->id])->data[0];
      $invoice= $this->stripe->invoices->sendInvoice($invoice->id,
            []
        );
      $pdf = $invoice->invoice_pdf;


        $p = $this->stripe->products->retrieve(
            $subscription->items->data['0']->price->product
        );

        $p = $p->name;
        $org = $this->org;
        if ( $p == "abonnement standard" ) {
            $org->setPlan(2);

        } else {
            $org->setPlan(1);

        }
        $cust = $this->stripe->customers->update(
            $customer,
            ['metadata' => ['sub_id' => $subscription->id,'quantity' => $body['quantity']
            ]]
        );

        $sub = Stripe\Subscription::update(
            $subscription->id,
            ['metadata' => ['pdf' => $pdf,
                'quantity' => $body['quantity']]
        ]);
        $org->setCustomerId($customer);

        $em->flush();



        return new JsonResponse((string)$pdf, 200);
    }

    /**
     * @param Request $request
     * @return mixed
     * @Route("/cancel-subscription", name="cancelSubscription")
     * @throws Stripe\Exception\ApiErrorException
     */
    public function cancelSubscriptionManagementAction(Request $request){
        $organization = $this->org;
        $custId = $organization->getCustomerId();
        $cust = Stripe\Customer::retrieve(
            $custId
        );
        $id = $cust->metadata->sub_id;
        dd($cust->metadata);
        $sub = $this->stripe->subscriptions->cancel(
            $id,

        );
        $em = $this->em;
        $sub =$this->stripe->subscriptions->all(['customer' => $custId]);
        if(sizeof($sub->data)==0) {
            $organization->setPlan(3);
        }
        $em->flush();
        return new JsonResponse($cust->metadata->sub_id, 200);
    }
    /**
 * @param Request $request
 * @return mixed
 * @Route("/cancel-card", name="cancelCard")
 * @throws Stripe\Exception\ApiErrorException
 */
    public function cancelCardManagementAction(Request $request){
        $em = $this->em;
        $cards = $this->org->getPaymentMethods();
        $id = $request->get('data');
        $this->org->removePaymentMethods($cards[(int) $id ]);
        $em->remove($cards[(int) $id ]);
        $em->flush();
        return new JsonResponse(sizeof($cards), 200);
    }
    /**
     * @param Request $request
     * @return mixed
     * @Route("/min-user-subscription", name="minUser")
     * @throws Stripe\Exception\ApiErrorException
     */
    public function minUserSubscriptionAction(Request $request){
        $em = $this->em;
        $custid = $this->org->getCustomerId();
        $cust = Stripe\Customer::retrieve(
            $custid
        );

       $sub = Stripe\Subscription::retrieve(
            $cust->metadata->sub_id,
        );
        $price = Stripe\Price::retrieve(
            $sub->plan->id,
            );

       $price =  Stripe\Price::create(
           ['unit_amount' => ($price->unit_amount - ($cust->metadata->priceInit*100)),
            'currency' => 'eur',
            'recurring' => ['interval' => $price->recurring->interval],
            'product' =>  $price->product,]
           );

       $sub = Stripe\Subscription::update(
           $cust->metadata->sub_id,
           ['cancel_at' => $sub->current_period_end ]

       );
       dd("test");
       $newSub = Stripe\Subscription::create(
           $cust->metadata->sub_id,
           ['cancel_at' => $sub->current_period_end ]

       );
       dd($sub->plan->amount);
        $cust = Stripe\Customer::update(
            $custid,
            ['metadata' => ['quantity' => (((int)$cust->metadata->quantity)-1)]]
        );
        $id = $cust->metadata->sub_id;

        return new JsonResponse((((int)$cust->metadata->quantity)-1), 200);
    }
    /**
     * @param Request $request
     * @return mixed
     * @Route("/plus-user-subscription", name="plusUser")
     * @throws Stripe\Exception\ApiErrorException
     */
    public function plusUserSubscriptionAction(Request $request){
        $em = $this->em;
        $custid = $this->org->getCustomerId();
        $cust = Stripe\Customer::retrieve(
            $custid
        );

        $cust = Stripe\Customer::update(
            $custid,
            ['metadata' => ['quantity' => (((int)$cust->metadata->quantity)+1)]]
        );
        $id = $cust->metadata->sub_id;

        return new JsonResponse((((int)$cust->metadata->quantity)+1), 200);
    }
        /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     * @Route("/settings/root/organizations", name="manageOrganizations")
     */
    public function manageOrganizationsAction(Request $request){
        $entityManager = $this->em;
        $repoO = $entityManager->getRepository(Organization::class);
        $organizations = [];

        /*
        foreach ($repoO->findAll() as $organization) {
            $organizations[] = $organization->toArray();
        }
        */

        //MasterController::sksort($organizations, 'lastConnectedDateTime');
        $organizations = $repoO->findAll();

        return $this->render('organization_list.html.twig',
            [
                'organizations' => $organizations,
                'lkPath' => null,
            ]) ;

    }

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     * @Route("/settings/root/workerFirms", name="manageWorkerFirms")
     */
    public function manageWorkerFirmsAction(Request $request){
        $em = $this->em;
        $qb = $em->createQueryBuilder();
        $qb2 = $em->createQueryBuilder();

        if(isset($_COOKIE['wf_s_p'])){
            $sortingProp = $_COOKIE['wf_s_p'];
        } else {
            setcookie('wf_s_p', 'a');
            $sortingProp = 'a';
        }

        if(isset($_COOKIE['wf_s_o'])){
            $sortingOrder = $_COOKIE['wf_s_o'];
        } else {
            setcookie('wf_s_o', 'a');
            $sortingOrder = 'a';
        }

        if(isset($_COOKIE['wf_nb'])){
            $maxResults = $_COOKIE['wf_nb'];
        } else {
            setcookie('wf_nb', 20);
            $maxResults = 20;
        }

        if(isset($_COOKIE['wf_cp'])){
            $page = $_COOKIE['wf_cp'];
        } else {
            setcookie('wf_cp', 1);
            $page = 1;
        }

        switch($sortingProp){
            case 'a' :
                $prop = 'name'; break;
            case 'i' :
                $prop = 'inserted';break;
            case 's' :
                $prop = 'nbLkEmployees';break;
        }
        $sortingOrder = $_COOKIE['wf_s_o'] == 'a' ? 'ASC' : 'DESC';
        
        $countryIds = [];

        $countryQueryResults = $qb->select('identity(wf.country) AS couId')
        ->from('App\Entity\WorkerFirm','wf')
        ->groupBy('wf.country')
        ->getQuery()
        ->getResult();

        foreach($countryQueryResults as $countryQueryResult){
            $countryIds[] = $countryQueryResult['couId'];
        }

        $countries = $em->getRepository(Country::class)->findById($countryIds);
        
        $qb2->select('wf')
            ->from('App\Entity\WorkerFirm','wf')
            ->orderBy('wf.' . $prop, $sortingOrder);

        if(isset($_COOKIE['wf_c'])){
            if($_COOKIE['wf_c'] != 0){
                $qb2->where('wf.country = :couId')
                    ->setParameter('couId', $_COOKIE['wf_c']);
            } else {
                $qb2->where('wf.country IS NULL');
            }
        }

        if(isset($_COOKIE['wf_s'])){
            if($_COOKIE['wf_s'] != 0){
                $qb2->andWhere('wf.state = :staId')
                    ->setParameter('staId', $_COOKIE['wf_s']);
            } else {
                $qb2->andWhere('wf.state IS NULL');
            }
        }

        if(isset($_COOKIE['wf_cit'])){
            if($_COOKIE['wf_cit'] != 0){
                $qb2->andWhere('wf.city = :citId')
                    ->setParameter('citId', $_COOKIE['wf_cit']);
            } else {
                $qb2->andWhere('wf.city IS NULL');
            }
        }

        $nbWorkerFirms = $qb2->getQuery()->getResult();
        $nbWorkerFirms = sizeof($nbWorkerFirms);

        $workerFirms = $qb2->setFirstResult(($page - 1) * $maxResults)
            ->setMaxResults((int) $maxResults);

        $workerFirms = $qb2->getQuery()->getResult();

        return $this->render('worker_firm_list.html.twig',
            [
                'workerFirms' => $workerFirms,
                'nbWorkerFirms' => $nbWorkerFirms,
                'maxResults' => $maxResults,
                'countries' => $countries,
            ]) ;

    }


    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/root/processes", name="manageProcesses")
     * @IsGranted("ROLE_ROOT", statusCode=404, message="Page not found")
     */
    public function manageProcessesAction(Request $request){
        $em = $this->em;
        $repoP = $em->getRepository(Process::class);
        $repoO = $em->getRepository(Organization::class);
        $currentUser = $this->user;
        $organization = $currentUser->getOrganization();
        $process = new Process();

        
        $manageForm = $this->createForm(ManageProcessForm::class, $organization, ['standalone' => true, 'isRoot' => true]);
        $manageForm->handleRequest($request);
        $createForm = $this->createForm(AddProcessForm::class, $process, ['standalone' => true, 'organization' => $organization,'entity' => 'process']);
        $createForm->handleRequest($request);

        $allProcesses = new ArrayCollection($repoP->findAll());
        $validatingProcesses = $allProcesses->filter(function(Process $p){return $p->isApprovable();});

        if($validatingProcesses->count() > 0){
            $validatingProcess = $validatingProcesses->first();
            $validateForm = $this->createForm(AddProcessForm::class, $validatingProcess, ['standalone' => true, 'organization' => $organization, 'entity' => 'process']);
            $validateForm->handleRequest($request);
        } else {
            $validateForm = null;
        }
     

        if ($manageForm->isSubmitted() && $manageForm->isValid()) {
            $em->flush();
            return $this->redirectToRoute('firmSettings');
        }

        return $this->render('process_list.html.twig',
            [
                'isRoot' => true,
                'form' => $manageForm->createView(),
                'requestForm' => $createForm->createView(),
                'validateForm' => $validateForm ? $validateForm->createView() : null,
                'entity' => 'process',
            ]);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $elmtId
     * @param $elmtType
     * @param $orgId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/root/process/validate/{elmtId}", name="validateProcess")
     * @IsGranted("ROLE_ROOT", statusCode=404, message="Page not found")
     */
    public function validateProcessAction(Request $request, $elmtId) {
        $em = $this->em;        
        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }

        $organization = $currentUser->getOrganization();
        $repoP = $em->getRepository(Process::class);
        $process = $elmtId == 0 ? new Process : $repoP->find($elmtId);

        if ($_POST['name'] == "") {
            return new JsonResponse(['errorMsg' => 'The process must have a name'], 500);
        }

        $process->setOrganization($organization);
        $doublonProcess = $repoP->findOneByName($_POST['name']);

        if (($doublonProcess == null) || ($doublonProcess == $process)) {
            $process->setName($_POST['name'])
                ->setParent($repoP->findOneById($_POST['parent']))
                ->setGradable($_POST['gradable']);

            $em->persist($process);
            $em->flush();
            return new JsonResponse(['message' => 'Success!','eid' => $process->getId()], 200);
        } else {
            return new JsonResponse(['errorMsg' => 'There is already a process having the same name !'], 500);
        }
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $elmtId
     * @param $elmtType
     * @param $orgId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/organization/process/validate/{elmtId}", name="validateIProcess")
     * @IsGranted("ROLE_ADMIN", statusCode=404, message="Page not found")
     */
    public function validateIProcessAction(Request $request, $elmtId) {
        
        $em = $this->em;        
        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        
        $organization = $currentUser->getOrganization();
        $repoP = $em->getRepository(InstitutionProcess::class);

        if ($_POST['name'] == "") {
            return new JsonResponse(['errorMsg' => 'The process must have a name'], 500);
        }

        $process = $elmtId != 0 ? $repoP->find($elmtId) : new InstitutionProcess;
        $process->setOrganization($organization);
        
        $doublonProcess = $repoP->findOneBy(['organization' => $organization, 'name' => $_POST['name']]);

        if (($doublonProcess == null) || ($doublonProcess == $process)) {
            $process->setName($_POST['name'])
                ->setParent($repoP->findOneById($_POST['parent']))
                ->setGradable($_POST['gradable']);
          
            $repoU = $em->getRepository(User::class);
            $selectedProcess = $em->getRepository(Process::class)->find($_POST['process']);
            $selectedMasterUser = $repoU->find($_POST['masterUser']);
            $process->setMasterUser($selectedMasterUser)->setProcess($selectedProcess);
            
            $em->persist($process);
            $em->flush();
            return new JsonResponse(['message' => 'Success!','eid' => $process->getId()], 200);
        } else {
            return new JsonResponse(['errorMsg' => 'There is already a process having the same name !'], 500);
        }
        
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $orgId
     * @param $elmtType
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/organization/{orgId}/{entity}/delete", name="deleteProcess")
     */
    public function deleteProcessAction(Request $request, $orgId, $elmtType) {

        $em = $this->em;
        $repoO = $em->getRepository(Organization::class);
        $elmtId = $request->get('id');
        $repoU = $em->getRepository(User::class);
        $organization = $repoO->find($orgId);
        $currentUser = $this->user;;
        $currentUserOrganization = $currentUser->getOrganization();
        $hasPageAccess = true;

        if ($currentUser->getRole() != 4 && ($organization != $currentUserOrganization || $currentUser->getRole() != 1)) {
            $hasPageAccess = false;
        }

        if (!$hasPageAccess) {
            return $this->render('errors/403.html.twig');
        } else {
        // $organization = $target->getOrganization();
            
            $repoE = ($elmtType == 'process') ? $em->getRepository(Process::class) : $em->getRepository(InstitutionProcess::class);
            /** @var InstitutionProcess|Process */
            $element = $repoE->find($elmtId);

            if($elmtType == 'process'){
                foreach($element->getInstitutionProcesses() as $IProcess){
                    $IProcess->removeProcess($element);
                    $IProcess->setProcess(null);
                    $this->em->persist($IProcess);
                }
            } else {
                foreach($element->getActivities() as $activity){
                    $element->removeActivity($activity);
                    $activity->setInstitutionProcess(null);
                    $this->em->persist($activity);
                }
            }   

            ($elmtType == 'process') ? $organization->removeProcess($element) : $organization->removeInstitutionProcess($element);
            $em->persist($organization);
            $em->flush();
            return new JsonResponse(['message' => 'Success!'], 200);
        }

    }

    //Adds user(s) to other organizations (limited to root)

    /**
     * @param Request $request
     * @param Application $app
     * @param $orgId
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/organization/users/{orgId}/create", name="rootCreateUser")
     */
    public function rootAddUserAction(Request $request, $orgId) {

        $currentUser = $this->user;;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }

        if ($currentUser->getRole() != 4) {
            return $this->render('errors/403.html.twig');
        }

        $em = $this->em;
        $repoO = $em->getRepository(Organization::class);
        $organization = $repoO->find($orgId);
        
        $createUserForm = $this->createForm(AddUserForm::class, null, ['standalone'=>true,'organization' => $organization, 'enabledCreatingUser' => true]);
        $organizationElementForm = $this->createForm(OrganizationElementType::class, null, ['usedForUserCreation' => true, 'standalone' => true ]);
        $createUserForm->handleRequest($request);
        $organizationElementForm->handleRequest($request);

        if($createUserForm->isSubmitted() && $createUserForm->isValid()){
            $settings = [];
            $recipients = [];
            foreach($createUserForm->get('users') as $userForm){
                $user = $userForm->getData();

                // Les lignes qui suivent sont horribles mais sont un hack au fait qu'a priori on ne puisse pas lier position et département à User (mais à vérifier, peut-être que ça remarche...)
                // $user->getPosId() renvoie une Position, et $user->getDptId() un département.

                $posId = $user->getPosId() ? $user->getPosId()->getId() : null;
                $dptId = $user->getDptId() ? $user->getDptId()->getId() : null;
                $titId = $user->getTitId() ? $user->getTitId()->getId() : null;
                $wgtId = $user->getWgtId() ? $user->getWgtId()->getId() : null;

                $token = md5(rand());

                $user->setOrgId($orgId)
                ->setPosId($posId)
                ->setDptId($dptId)
                ->setTitId($titId)
                ->setWgtId($wgtId)
                ->setToken($token);
                $em->persist($user);
                $settings['tokens'][] = $token;
                $recipients[] = $user;

            }

            $settings['rootCreation'] = true;
            $em->flush();
            $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'registration']);

            return $this->redirectToRoute('rootManageUsers', ['orgId' => $orgId]);
        }

        return $this->render('user_create.html.twig',
            [
                'form' => $createUserForm->createView(),
                'organizationElementForm' => $organizationElementForm->createView(),
                'orgId' => $orgId,
                'enabledCreatingUser' => true,
                'creationPage' => true,
            ]);
    }



    //Modifies organization (limited to root master)
    public function modifyOrganizationAction(Request $request){

    }

    // Display all organization activities (for root user)

    /**
     * @param Request $request
     * @param Application $app
     * @param $orgId
     * @return mixed
     * @Route("/settings/organization/activities/{orgId}", name="organizationActivities")
     */
    public function getAllOrganizationActivitiesAction(Request $request, $orgId)
    {
        try{
            $entityManager = $this->getEntityManager($app) ;
            $repoO = $entityManager->getRepository(Organization::class);
            $currentUser = $this->user;
            $organization = $repoO->findOneById($orgId);
            
            $delegateActivityForm = $this->createForm(DelegateActivityForm::class, null,  ['app' => $app, 'standalone' => true]);
            $delegateActivityForm->handleRequest($request);
            $requestActivityForm = $this->createForm(RequestActivityForm::class, null, ['app' => $app, 'standalone' => true]);
            $requestActivityForm->handleRequest($request);
            $userActivities = $organization->getActivities();


            //Remove future recurring activities which are far ahead (at least two after current one
            foreach($userActivities as $activity){
                if($activity->getRecurring()){
                    $recurring = $activity->getRecurring();

                    if($recurring->getOngoingFutCurrActivities()->contains($activity) && $recurring->getOngoingFutCurrActivities()->indexOf($activity) > 1){

                        $userActivities->removeElement($activity);
                    }
                }
            }

            $nbActivitiesCategories = 0;

            $cancelledActivities = $userActivities->matching(Criteria::create()->where(Criteria::expr()->eq("status", -5)));
            $nbActivitiesCategories = (count($cancelledActivities) > 0) ? $nbActivitiesCategories + 1 : $nbActivitiesCategories;
            $discardedActivities = $userActivities->matching(Criteria::create()->where(Criteria::expr()->eq("status", -4)));
            $nbActivitiesCategories = (count($discardedActivities) > 0) ? $nbActivitiesCategories + 1 : $nbActivitiesCategories;
            $requestedActivities = $userActivities->matching(Criteria::create()->where(Criteria::expr()->eq("status", -3)));
            $nbActivitiesCategories = (count($requestedActivities) > 0) ? $nbActivitiesCategories + 1 : $nbActivitiesCategories;
            $attributedActivities = $userActivities->matching(Criteria::create()->where(Criteria::expr()->eq("status", -2)));
            $nbActivitiesCategories = (count($attributedActivities) > 0) ? $nbActivitiesCategories + 1 : $nbActivitiesCategories;
            $incompleteActivities = $userActivities->matching(Criteria::create()->where(Criteria::expr()->eq("status", -1)));
            $nbActivitiesCategories = (count($incompleteActivities) > 0) ? $nbActivitiesCategories + 1 : $nbActivitiesCategories;
            $futureActivities = $userActivities->matching(Criteria::create()->where(Criteria::expr()->eq("status", 0)));
            $nbActivitiesCategories = (count($futureActivities) > 0) ? $nbActivitiesCategories + 1 : $nbActivitiesCategories;
            $currentActivities = $userActivities->matching(Criteria::create()->where(Criteria::expr()->eq("status", 1)));
            $nbActivitiesCategories = (count($currentActivities) > 0) ? $nbActivitiesCategories + 1 : $nbActivitiesCategories;
            $completedActivities = $userActivities->matching(Criteria::create()->where(Criteria::expr()->eq("status", 2)));
            $nbActivitiesCategories = (count($completedActivities) > 0) ? $nbActivitiesCategories + 1 : $nbActivitiesCategories;
            $releasedActivities = $userActivities->matching(Criteria::create()->where(Criteria::expr()->eq("status", 3)));
            $archivedActivities = $userActivities->matching(Criteria::create()->where(Criteria::expr()->eq("status", 4)));

        } catch (Exception $e){
            print_r($e->getLine().' '.$e->getMessage());
            die;
        }


        return $this->render('activity_list.html.twig',
            [
                'user_activities' => $userActivities,
                'organization' => $organization,
                'delegateForm' => $delegateActivityForm->createView(),
                'requestForm' => $requestActivityForm->createView(),
                'orgMode' => true,
                'request' => $request,
                'cancelledActivities' => $cancelledActivities,
                'discardedActivities' => $discardedActivities,
                'requestedActivities' => $requestedActivities,
                'attributedActivities' => $attributedActivities,
                'incompleteActivities' => $incompleteActivities,
                'futureActivities' => $futureActivities,
                'currentActivities' => $currentActivities,
                'completedActivities' => $completedActivities,
                'releasedActivities' => $releasedActivities,
                'archivedActivities' => $archivedActivities,
                'nbCategories' => $nbActivitiesCategories,
                'existingAccessAndResultsViewOption' => false,
                'hideResultsFromStageIds' => [],
                'resultsAccess' => 2,
            ]);

    }

    // Display all organization users (for root user)
    // Same code as OrgController::getAllUsersAction
    /**
     * @param Request $request
     * @param Application $app
     * @param $orgId
     * @return mixed
     * @Route("/settings/organization/users/{orgId}", name="rootManageUsers")
     */
    public function getAllOrganizationUsersAction(Request $request, $orgId){


        $entityManager = $this->getEntityManager($app);
        $repoEU = $entityManager->getRepository(ExternalUser::class);
        $repoU = $entityManager->getRepository(User::class);
        $repoO = $entityManager->getRepository(Organization::class);
        /** @var Organization */
        $organization = $repoO->findOneById($orgId);
        $clientFirms = new ArrayCollection;
        $clientTeams = new ArrayCollection;
        $clientIndividuals = new ArrayCollection;
        $clients = $organization->getClients();
        $totalClientUsers = 0;
        $orgEnabledCreatingUser = false;
        // Only administrators or roots can create/update users who have the ability to create users themselves
        $orgOptions = $organization->getOptions();

        // Selecting viewable departments
        $viewableDepartments     = $organization->getDepartments();

        foreach ($clients as $client){
            switch ($client->getClientOrganization()->getType()){
                case 'F':
                    $clientFirms->add($client);
                    break;
                case 'T':
                    $clientTeams->add($client);
                    break;
                case 'I':
                    $clientIndividuals->add($client);
                    break;
                default :
                    break;
            }

        }
        foreach($orgOptions as $orgOption){
            if($orgOption->getOName()->getName() == 'enabledUserCreatingUser'){
                $orgEnabledCreatingUser = $orgOption->isOptionTrue();
            }
        }

        $totalClientUsers = count($organization->getExternalUsers());
        $nbViewableInternalUsers = count($organization->getUsers($app));

        $viewableTeams = $organization->getTeams();

        $firmLogoPath = $organization->getLogo();

        $users = new ArrayCollection($repoU->findBy(['orgId' => $orgId],['dptId' => 'ASC','posId' =>'ASC']));
        $usersWithDpt = $users->matching(Criteria::create()->where(Criteria::expr()->neq("dptId", null)));
        $usersWithoutDpt = $users->matching(Criteria::create()->where(Criteria::expr()->eq("dptId", null))->andWhere(Criteria::expr()->neq("lastname", "ZZ")));

        return $this->render('user_list.html.twig',
            [
                'rootDisplay' => true,
                'app' => $app,
                'clientFirms' => $clientFirms,
                'clientTeams' => $clientTeams,
                'clientIndividuals' => $clientIndividuals,
                'usersWithDpt' => $usersWithDpt,
                'organization' => $organization,
                'totalClientUsers' => $totalClientUsers,
                'firm_logo' => $firmLogoPath,
                'usersWithoutDpt' => $usersWithoutDpt,
                'viewableDepartments' => $viewableDepartments,
                'viewableTeams' => $viewableTeams,
                'orgEnabledUserCreatingUser' => $orgEnabledCreatingUser,
                'orgEnabledUserSeeRanking' => true,
                'nbViewableInternalUsers' => $nbViewableInternalUsers,
                'orgEnabledUserSeeAllUsers' => true,
                'orgEnabledUserSeePeersResults' => true,
                'enabledUserSeeSnapshotSupResults' => true,
                'enabledSuperiorOverviewSubResults' => true,
                'enabledSuperiorSettingTargets' => true,
                'enabledSuperiorModifySubordinate' => true,
            ]);
    }


    // Delete organization (limited to root master)

    /**
     * @param Application $app
     * @param $orgId
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/root/organization/{orgId}/delete", name="deleteOrganization")
     */
    public function deleteOrganizationAction($orgId) {
        $em = $this->em;
        $repoO = $em->getRepository(Organization::class);

        /** @var Organization */
        $organization = $repoO->find($orgId);
        // Problem of constraint key, deleting each weight elmy although cascading removals
        /*foreach($organization->getWeights() as $weight){
            $organization->removeWeight($weight);
        }*/
        $em->remove($organization);
        $em->flush();

        return $this->redirectToRoute('manageOrganizations');
    }

    // Delete worker firm (limited to root master)

    /**
     * @param Application $app
     * @param $orgId
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/root/workerFirm/delete", name="deleteWorkerFirm")
     */
    public function deleteWorkerFirmAction(Request $request) {
        $em = $this->em;
        $wfiId = $request->get('id');
        $repoWF = $em->getRepository(WorkerFirm::class);
        /** @var WorkerFirm */
        $workerFirm = $repoWF->find($wfiId);
        $em->remove($workerFirm);
        $em->flush();
        return $this->redirectToRoute('manageWorkerFirms');
    }


    //Adds organization (limited to root master)

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/organization/create", name="createOrganization")
     */
    public function addOrganizationAction(Request $request)
    {
        $em = $this->em;
        /** @var FormFactory */
        $organizationForm = $this->createForm(AddOrganizationForm::class, null, ['standalone' => true, 'orgId' => 0, 'em' => $em, 'isFromClient' => false]);
        $organizationForm->handleRequest($request);
        $errorMessage = '';
        $organization = new Organization;
        $options = [];
        $repoO = $em->getRepository(Organization::class);
        $repoWF = $em->getRepository(WorkerFirm::class);
        $repoU = $em->getRepository(User::class);
        $repoON = $em->getRepository(OptionName::class);
        /** @var OptionName[] */
        $options = $repoON->findAll();
        $wfiId = $request->get('wfiId');

        if ($organizationForm->isSubmitted()) {
            /*
            if($repoO->findOneByLegalname($organizationForm->get('workerFirm')->getData())){
                $organizationForm->get('commercialName')->addError(new FormError('You already have created an organization with such legalname. Make sure you correctly provided the data'));
            }
            */
            /*
            if($repoU->findOneByEmail($organizationForm->get('email')->getData())){
                $organizationForm->get('email')->addError(new FormError('Ooopsss ! There is already a user registered with this email address. Please make sure you have not already created an organization with such an address'));
            }*/

            if ($organizationForm->isValid()) {

                /** @var string */
                $email = $organizationForm->get('email')->getData();
                /** @var string */
                $firstname = $organizationForm->get('firstname')->getData();
                /** @var string */
                $lastname = $organizationForm->get('lastname')->getData();
                /** @var string */
                $orgType = $organizationForm->get('type')->getData();
                /** @var WorkerFirm */
                $workerFirm = $wfiId ? $repoWF->find($wfiId): new WorkerFirm;

                if(!$wfiId){
                    $workerFirm->setName($organizationForm->get('commname')->getData())
                    ->setCommonName($organizationForm->get('commname')->getData());
                    $em->persist($workerFirm);
                    $em->flush();    
                }

                $now = new DateTime();

                $organization
                    ->setCommname($workerFirm->getName())
                    ->setLegalname($workerFirm->getName())
                    ->setIsClient(true)
                    ->setType($orgType)
                    ->setWeightType('role')
                    ->setExpired($now->add(new DateInterval('P21D')))
                    ->setWorkerFirm($workerFirm);

                $this->forward('App\Controller\OrganizationController::updateOrgFeatures', ['organization' => $organization, 'nonExistingOrg' => true, 'createdAsClient' => false]);
                
                /** @var string */
                $positionName = $organizationForm->get('position')->getData();
                /** @var string */
                $departmentName = $organizationForm->get('department')->getData();

                $user = null;

                $token = md5(rand());

                if(!($email == "" && $firstname == "" && $lastname == "")){
                    $user = new User;
                    $user
                        ->setFirstname($firstname)
                        ->setLastname($lastname)
                        ->setEmail($email)
                        ->setRole(USER::ROLE_AM)
                        ->setToken($token)
                        ->setWeightIni(100);
                    $em->persist($user);
                    //$em->flush();
                }

                if($user){

                    if($departmentName != "") {
                        $department = new Department;
                        $department->setName($departmentName);
                        $department->setMasterUser($user);
                        $department->addUser($user);
                        $organization->addDepartment($department);
                    }

                    if($positionName != "") {
                        $position = new Position;
                        $position->setName($positionName);
                        $position->addUser($user);
                        $organization->addPosition($position);
                    }

                    $user
                        ->setDepartment($department)
                        ->setPosition($position)
                        ->setWeight($organization->getDefaultWeight());
                        
                    //$em->persist($user);
                    $organization->addUser($user);
                    $em->persist($organization);
                    $em->flush();

                    if($user){
                        $organization->setMasterUser($user);
                    }

                    $em->persist($organization);
                    $em->flush();
                }

                // Sending mail to created firm master user, if such user exists
                if($user){
                    $settings['tokens'][] = $token;
                    $recipients = [];
                    $recipients[] = $user;
                    $settings['rootCreation'] = true;
                    $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'registration']);

                }

                return $this->redirectToRoute('manageOrganizations');
            }

        }

        return $this->render('organization_add.html.twig',
            [
                'form' => $organizationForm->createView(),
                'message' => $errorMessage,
                //'nbweights'=> $nbWeights,
                //'totalweights' => $totalWeights
            ]);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $orgId
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/organizations/{orgId}/validate", name="validateOrganization")
     */
    public function validateOrganizationAction(Request $request, $orgId){

        $currentUser = self::getAuthorizedUser($app);
        if($currentUser->getRole() != 4){
            return $this->render('errors/404.html.twig');
        }

        $em = $this->em;
        $repoO = $em->getRepository(Organization::class);
        $repoU = $em->getRepository(User::class);
        $organization = $repoO->findOneById($orgId);
        
        $organizationForm = $this->createForm(AddOrganizationForm::class, null,['standalone' => true, 'orgId' => $orgId, 'app' => $app, 'toValidate' => true]);
        $organizationForm->handleRequest($request);

        if($organizationForm->isSubmitted()){

            $masterUser = $repoU->findOneById($organization->getMasterUserId());

            if($organizationForm->get('validate')->isClicked()){

                $organization
                    ->setIsClient(true)
                    ->setValidated(new \DateTime)
                    ->setType($organizationForm->get('type')->getData());

                $masterUser->setValidated(new \DateTime);
                $em->persist($organization);
                $em->persist($masterUser);

                $recipients = [];
                $recipients[] = $masterUser;
                $settings = [];
                $settings['token'] = $masterUser->getToken();

                $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'subscriptionConfirmation']);


            } else {

                $em->remove($masterUser);
                $em->remove($organization);
            }

            $em->flush();
            return $this->redirectToRoute('manageOrgazations');
        }

        return $this->render('organization_add.html.twig',
            [
                'form' => $organizationForm->createView(),
                'toValidate' => true,
            ]);
    }

    public function deleteOrganizationActivityAction(Request $request, $orgId, $actId){
        $em = $this->em;
        $activity = $em->getRepository(Activity::class)->findOneById($actId);
        $user = self::getAuthorizedUser($app);
        $organization = $em->getRepository(Organization::class)->findOneById($orgId);
        $organization->removeActivity($activity);
        $em->persist($organization);
        $em->flush();
        return true;
    }

    /**
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/launching/mail/send", name="sendLaunchingMail")
     */
    public function sendLaunchingMail(){

        $followerMail = $_POST['follower'];
        $this->forward('App\Controller\MailController::sendMail', ['recipients' => [$followerMail], 'settings' => ['recipientUsers' => false], 'actionType' => 'launchingFollowupConfirmation']);
        $em = $this->em;
        $ddAdmins = $em->getRepository(User::class)->findByRole(4);
        $this->forward('App\Controller\MailController::sendMail', ['recipients' => $ddAdmins, 'settings' => ['follower' => $followerMail], 'actionType' => 'launchingFollowupSubscription']);
        return new JsonResponse(['msg' => 'success'],200);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $orgId
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/organization/{orgId}/update", name="updateOrganization")
     */
    public function updateOrganizationAction(Request $request, $orgId){

        $em = $this->em;
        $repoO = $em->getRepository(Organization::class);
        
        $organizationForm = $this->createForm(AddOrganizationForm::class, null,['standalone' => true, 'orgId' => $orgId, 'app' => $app]);
        $organizationForm->handleRequest($request);
        $errorMessage = '';
        $organization = $repoO->findOneById($orgId);
        $user = new User;
        $department = new Department;
        $position = new Position;

        if ($organizationForm->isSubmitted()) {
            if ($organizationForm->isValid()) {
                $email = $organizationForm->get('email')->getData();
                $token = md5(rand());

                $organization->setCommname($organizationForm->get('commname')->getData());
                $organization->setLegalname($organizationForm->get('legalname')->getData());
                $organization->setMasterUserId(0);
                $em->persist($organization);

                $department->setOrganization($organization);
                $department->setName($organizationForm->get('department')->getData());
                $em->persist($department);
                //$em->flush();

                //$position->setDepartment($department->getId());
                $position->setDepartment($department);
                $position->setName($organizationForm->get('position')->getData());

                $em->persist($position);
                $em->flush();

                $user->setFirstname($organizationForm->get('firstname')->getData());
                $user->setLastname($organizationForm->get('lastname')->getData());
                $user->setEmail($email);

                $user->setRole(1);
                $user->setToken($token);

                $user->setPosId($position->getId());

                $user->setOrgId($organization->getId());
                $em->persist($user);
                $em->flush();

                $organization->setMasterUserId($user->getId());
                $em->persist($organization);
                $em->flush();

                $recipients = [];
                $recipients[] = $user;
                $recipients[] = $user;
                $settings = [];

                $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'orgMasterUserChange']);

                return $this->redirectToRoute('manageOrganizations');
            }

        }

        return $this->render('organization_add.html.twig',
            [
                'form' => $organizationForm->createView(),
                'message' => $errorMessage,
                'update' => true,
                'orgId' => $organization->getId()
            ]);

    }

    // Display user info, enables modification. Note : root user can modify users from other organizations

    /**
     * @param Request $request
     * @param Application $app
     * @param $orgId
     * @param $usrId
     * @return mixed
     * @Route("/settings/organization/{orgId}/user/{usrId}", name="rootUpdateUser")
     */
    public function updateUserAction(Request $request, $orgId, $usrId)
    {

        $em = $this->em;
        $repoO = $em->getRepository(Organization::class);
        $repoOC = $em->getRepository(Client::class);
        $searchedUser = $em->getRepository(User::class)->findOneById($usrId);
        $connectedUser = MasterController::getAuthorizedUser($app);
        $searchedUserOrganization = $repoO->findOneById($searchedUser->getOrgId());
        $orgOptions = $searchedUserOrganization->getOptions();
        
        $departments = ($searchedUser->getOrgId() == $connectedUser->getOrgId() || $connectedUser->getRole() == 4) ? $searchedUserOrganization->getDepartments() : null;
        $enabledCreatingUserOption = false;
        foreach($orgOptions as $orgOption){
            if($orgOption->getOName()->getName() == 'enabledUserCreatingUser'){
                $enabledCreatingUserOption = $orgOption->isOptionTrue();
            }
        }
        // Look through organization clients if user belongs to org clients
        if($searchedUser->getOrgId() != $connectedUser->getOrgId()){

            $connectedUserOrganization = $repoO->findOneById($connectedUser->getOrgId());
            $connectedUserOrgClients = $repoOC->findByOrganization($connectedUserOrganization);
            $connectedUserClients = [];
            foreach($connectedUserOrgClients as $connectedUserOrgClient){
                $connectedUserClients[] = $connectedUserOrgClient->getClientOrganization();
            }

            if(!in_array($searchedUserOrganization,$connectedUserClients) && $connectedUser->getRole() != 4){
                return $this->render('errors/403.html.twig');
            }

            if(in_array($searchedUserOrganization,$connectedUserClients)){
                $modifyIntern = false;
                $userForm = $this->createForm(ClientUserType::class, null, ['standalone' => true, 'user' => $searchedUser, 'app' => $app, 'clients' => $connectedUserClients]);
            } else {
                // This case only applies to root users
                $modifyIntern = true;
                $userForm = $this->createForm(UserType::class, null, ['standalone' => true, 'app' => $app, 'organization' => $searchedUserOrganization, 'user' => $searchedUser]);
            }

        } else {
            if($connectedUser->getRole() == 2 || $connectedUser->getRole() == 3){
                return $this->render('errors/403.html.twig');
            }

            $modifyIntern = true;
            $userForm = $this->createForm(UserType::class, $searchedUser, ['standalone' => true, 'organization' => $searchedUserOrganization]);
        }



        $userForm->handleRequest($request);
        $organizationElementForm = $this->createForm(OrganizationElementType::class, null, ['usedForUserCreation' => false, 'standalone' => true, 'organization' => $searchedUserOrganization]);
        $organizationElementForm->handleRequest($request);
        /*} catch (\Exception $e){
            print_r($e->getMessage());
            die;
        }*/

        return $this->render('user_create.html.twig',
            [
                'modifyIntern' => $modifyIntern,
                'form' => $userForm->createView(),
                'orgId' => $searchedUserOrganization->getId(),
                'organizationElementForm' => $organizationElementForm->createView(),
                'clientForm' => ($modifyIntern) ? null : $this->createForm(AddClientForm::class, null, ['standalone'=>true])->createView(),
                'enabledCreatingUser' => false,
                'creationPage' => false,

            ]);

    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $orgId
     * @param $usrId
     * @return JsonResponse
     * @Route("/settings/organization/{orgId}/user/{usrId}", name="rootUpdateUserAJAX")
     */
    public function updateUserActionAJAX(Request $request, $orgId, $usrId)
    {

        try{
        $em = $this->em;
        $repoO = $em->getRepository(Organization::class);
        $repoOC = $em->getRepository(Client::class);
        $searchedUser = $em->getRepository(User::class)->findOneById($usrId);
        $connectedUser = MasterController::getAuthorizedUser($app);
        

        $searchedUserOrganization = $repoO->findOneById($searchedUser->getOrgId());

        $departments = ($searchedUser->getOrgId() == $connectedUser->getOrgId() || $connectedUser->getRole() == 4) ? $searchedUserOrganization->getDepartments() : null;

        // Look through organization clients if user belongs to org clients
        if($searchedUser->getOrgId() != $connectedUser->getOrgId()){

            $connectedUserOrganization = $repoO->findOneById($connectedUser->getOrgId());
            $connectedUserOrgClients = $repoOC->findByOrganization($connectedUserOrganization);
            $connectedUserClients = [];
            foreach($connectedUserOrgClients as $connectedUserOrgClient){
                $connectedUserClients[] = $connectedUserOrgClient->getClientOrganization();
            }

            if(!in_array($searchedUserOrganization,$connectedUserClients) && $connectedUser->getRole() != 4){
                return $this->render('errors/403.html.twig');
            }

            $userForm = (!in_array($searchedUserOrganization,$connectedUserClients)) ?
            $this->createForm(UserType::class, null, ['standalone' => true, 'app' => $app, 'departments' => $departments, 'user' => $searchedUser]) :
            $this->createForm(ClientUserType::class, null, ['standalone' => true, 'user' => $searchedUser, 'app' => $app, 'clients' => $connectedUserOrgClients]);

        } else {
            if($connectedUser->getRole() == 2 || $connectedUser->getRole() == 3){
                return $this->render('errors/403.html.twig');
            }

            $userForm = $this->createForm(UserType::class, null, ['standalone' => true, 'app' => $app, 'departments' => $departments, 'user' => $searchedUser]);
        }



        $userForm->handleRequest($request);

        if($userForm->isValid()){

            if($searchedUser->getOrgId() == $connectedUser->getOrgId() || !in_array($searchedUserOrganization,$connectedUserClients)){

                $repoW = $em->getRepository(Weight::class);
                //$repoP = $em->getRepository(Position::class);
                $searchedUser
                    ->setFirstname($userForm->get('firstname')->getData())
                    ->setLastname($userForm->get('lastname')->getData())
                    ->setRole($userForm->get('role')->getData())
                    ->setPosId($userForm->get('position')->getData())
                    ->setDptId($userForm->get('department')->getData())
                    ->setWgtId($userForm->get('weightIni')->getData());

                if($searchedUser->getEmail() != $userForm->get('email')->getData()){
                    $repicients = [];
                    $recipients[] = $searchedUser;
                    $token = md5(rand());
                    $settings['token'] = $token;
                    $searchedUser->setPassword(null)->setToken($token)->setEmail($userForm->get('email')->getData());
                    $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'emailChangeNotif']);
                }

                $existingWeight = $repoW->findOneById($userForm->get('weightIni')->getData());
                $searchedUser->setWeightIni($existingWeight->getValue());

                $em->persist($searchedUser);
                $em->flush();

            } else {

                $externalUser = $searchedUser->getExternalUser($app);

                if($externalUser->getEmail() != $userForm->get('email')->getData()){
                    $repicients = [];
                    $recipients[] = $searchedUser;
                    $token = md5(rand());
                    $settings['token'] = $token;
                    $externalUser->setPassword(null)->setToken($token)->setEmail($userForm->get('email')->getData());
                    $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'emailChangeNotif']);
                }

                $externalUser
                    ->setFirstname($userForm->get('firstname')->getData())
                    ->setLastname($userForm->get('lastname')->getData())
                    ->setPositionName($userForm->get('positionName')->getData())
                    ->setWeightValue($userForm->get('weightValue')->getData());

                if($userForm->get('type')->getData() != 'I'){
                    $searchedUser->setOrgId($userForm->get('orgId')->getData());
                    $clientOrganization = $repoO->findOneById(intval($userForm->get('orgId')->getData()));
                    $clientOrganization->setType($userForm->get('type')->getData());
                } else {
                    $clientOrganization = new Organization;
                    $clientOrganization->setType('I')->setIsClient(false)->setCommname($userForm->get('firstname')->getData().' '.$userForm->get('lastname')->getData())->setWeight_type('role');
                    $client = new Client;
                    $client->setOrganization($connectedUserOrganization)->setClientOrganization($clientOrganization);
                    $connectedUserOrganization->addClient($client);
                }

                $em->persist($externalUser);
                $em->persist($clientOrganization);
                $em->persist($connectedUserOrganization);
                $em->flush();

            }

            return new JsonResponse(['message' => 'Success!'], 200);

        } else {
            $errors = $this->buildErrorArray($userForm);
            return $errors;
        }
    }
    catch(Exception $e) {
        print_r($e->getLine().' '.$e->getMessage());
        die;
    }
    }

    // Root user deletion function (does it permanently)

    /**
     * @param Request $request
     * @param Application $app
     * @param $orgId
     * @param $usrId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/ajax/settings/organization/{orgId}/user/{usrId}", name="rootAjaxUserDelete")
     */
    public function deleteUserAction(Request $request, $orgId, $usrId){
        $em = $this->em;
        $repoP = $em->getRepository(Participation::class);
        $repoU = $em->getRepository(User::class);
        $repoO = $em->getRepository(Organization::class);
        $repoD = $em->getRepository(Department::class);
        $repoP = $em->getRepository(Position::class);
        $repoW = $em->getRepository(Weight::class);
        $user = $repoU->findOneById($usrId);
        $organization = $repoO->findOneById($orgId);
        $deleteOrg = false;
        $userOrgId = $user->getOrgId();
        $posId = $user->getPosId();
        $dptId = $user->getDptId();
        $wgtId = $user->getWgtId();
        $nbUserParticipations = count($repoP->findByUsrId($usrId));

        if($posId != null){
            $position = $repoP->findOneById($posId);
            $positionUsers = $position->getUsers($app);
            if(count($positionUsers) == 1){
                if($nbUserParticipations == 0){
                    $organization->removePosition($position);
                } else {
                    $position->setDeleted(new \DateTime);
                    $em->persist($position);
                }
            }
        }

        if($dptId != null){
            $department = $repoD->findOneById($dptId);
            $departmentUsers = $department->getUsers($app);
            if(count($departmentUsers) == 1){
                if($nbUserParticipations == 0){
                    $organization->removeDepartment($department);
                } else {
                    $department->setDeleted(new \DateTime);
                    $em->persist($department);
                }
            }
        }

        if($wgtId != null){
            $weight = $repoW->findOneById($wgtId);
            $weightUsers = $weight->getUsers($app);
            if(count($weightUsers) == 1){
                if($nbUserParticipations == 0){
                    $em->remove($weight);
                }
            }
        }

        if($nbUserParticipations == 0){
            $em->remove($user);
        } else {
            $user->setDeleted(new \DateTime);
            $em->persist($user);
        }

        $em->flush();

        $organizationUsers = $organization->getUsers($app);
        if($organizationUsers == null){
            $em->remove($organization);
            $deleteOrg = true;
            $em->flush();
        }

        return new JsonResponse(['message' => 'Success!', 'deleteOrg' => $deleteOrg], 200);
    }

    // Root external user deletion (does it permanently)

    /**
     * @param Request $request
     * @param Application $app
     * @param $orgId
     * @param $usrId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/ajax/settings/organization/{orgId}/client/user/{usrId}", name="rootAjaxClientUserDelete")
     */
    public function deleteClientUserAction(Request $request, $orgId, $usrId){
        $em = $this->em;
        $repoP = $em->getRepository(Participation::class);
        $repoEU = $em->getRepository(ExternalUser::class);
        $repoU = $em->getRepository(User::class);
        $repoO = $em->getRepository(Organization::class);
        $em->flush();
        $organization = $repoO->findOneById($orgId);
        $nbUserParticipations = count($repoP->findByUsrId($usrId));
        $internalUser = $repoU->findOneById($usrId);
        $externalUser = $repoEU->findOneBy(['user' => $internalUser, 'organization' => $organization]);
        if($nbUserParticipations == 0){
            $this->deleteUserAction($request, $app, $orgId, $usrId);
            $em->remove($externalUser);
        } else {
            $externalUser->setDeleted(new \DateTime);
            $em->persist($externalUser);
        }
        $em->flush();
        return new JsonResponse(['message' => 'Success!'], 200);
    }

    public function insertIndividualExperience($em, $repoWF, $repoWE, $worker, $experience){

        // 1 - We look whether firm exists
        $firmName = $experience['firm']['name'];
        $firmSuffix = $experience['firm']['urlSuffix'];
        $startDate = new \DateTime($experience['SD']);
        $endDate = new \DateTime($experience['ED']);

        if($firmSuffix != null){
            $firm = $repoWF->findOneBy(['url' => $firmSuffix]);
        } else {
            $firm = $repoWF->findOneBy(['name' => $firmName]);
        }

        if($firm == null){
            $firm = new WorkerFirm;
            $firm
                ->setName($firmName)
                ->setUrl($firmSuffix)
                ->setCreated(0);
        }

        if($experience['ED'] == null){
            if($firm->isActive() == false){
                $firm->setActive(true);
            }
            $nbActiveExperiences = $firm->getNbActiveExperiences();
            if($nbActiveExperiences == null){
                $firm->setNbActiveExperiences(1);
            } else {
                $firm->setNbActiveExperiences($nbActiveExperiences+1);
            }
        } else {
            $nbOldExperiences = $firm->getNbOldExperiences();
            if($nbOldExperiences == null){
                $firm->setNbOldExperiences(1);
            } else {
                $firm->setNbOldExperiences($nbOldExperiences+1);
            }
        }

        //$workerExp = $repoWE->findOneBy(['startDate' => $startDate, 'firm' => $firm, 'individual' => $worker]);

        //if($workerExp == null){
            $workerExp = new WorkerExperience;
            $workerExp->setStartDate($startDate)
                ->setEndDate($endDate)
                ->setPosition($experience['pos'])
                ->setActive($experience['ED'] == null);
            if(isset($experience['location'])){
                $workerExp->setLocation($experience['location']);
            }
            $firm->addExperience($workerExp);
            $worker->addExperience($workerExp);
        /*} else {
            if($experience['ED'] != null && $workerExp->getEndDate() == null){
                $workerExp->setActive(false);
            }

            if(isset($experience['location']) && $experience['location'] != null && $workerExp->getLocation() == null){
                $workerExp->setLocation($experience['location']);
            }
        }*/

        $em->persist($firm);
        $em->persist($worker);
        $em->flush();
    }

    /*public function searchWorkerElmts(Request $request){

        
        $searchWorkerForm = $this->createForm(SearchWorkerForm::class, null);
        $searchWorkerForm->handleRequest($request);

        return $this->render('worker_search.html.twig',
        [
            'form' => $searchWorkerForm->createView(),
        ]);

    }*/

    /**
     * @param Request $request
     * @param Application $app
     * @param $orgId
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/organization/{orgId}/duplicate", name="duplicateOrganization")
     */
    public function duplicateOrganizationAction(Request $request,$orgId){
        set_time_limit(240);
        ini_set('memory_limit', '500M');
        $em = $this->em;
        $repoO = $em->getRepository(Organization::class);
        $repoA = $em->getRepository(Activity::class);
        $repoU = $em->getRepository(User::class);
        $repoEU = $em->getRepository(ExternalUser::class);
        $repoT = $em->getRepository(Team::class);
        $repoOC = $em->getRepository(Client::class);
        $firm = clone $repoO->findOneById($orgId);
        $clonedFirm = clone $firm;
        $clonedFirm->setCommname($firm->getCommname().'_Test')
            ->setLegalname($firm->getLegalname())
            ->setInserted(new \DateTime);
        $em->persist($clonedFirm);
        $em->flush();

        // Duplicate users (internal & external), & teams

        $firmUsers = $repoU->findByOrgId($firm->getId());

        $firmExternalUsers = $repoEU->findByOrganization($firm);

        $selectedWeight = null;
        $selectedDepartment = null;
        $selectedPosition = null;

        foreach($firm->getDepartments() as $department){
            $clonedDepartment = clone $department;
            $clonedDepartment->setInserted(new \DateTime);
            $clonedFirm->addDepartment($clonedDepartment);
            $clonedDepartments[] = $clonedDepartment;
            $originalDepartments[] = $department;
        }

        foreach($firm->getPositions() as $position){
            $clonedPosition = clone $position;
            $clonedPosition->setInserted(new \DateTime);
            $clonedFirm->addPosition($clonedPosition);
            $clonedPositions[] = $clonedPosition;
            $originalPositions[] = $position;
        }

        foreach($firm->getWeights() as $weight){
            $clonedWeight = clone $weight;
            $clonedWeight->setInserted(new \DateTime);
            $clonedFirm->addWeight($clonedWeight);
            $clonedWeights[] = $clonedWeight;
            $originalWeights[] = $weight;
        }

        $em->persist($clonedFirm);
        $em->flush();

        foreach($firmUsers as $firmUser){

            $userClonedDepartment = $clonedDepartments[array_search($firmUser->getDepartment($app),$originalDepartments)];
            $userClonedPosition = $clonedPositions[array_search($firmUser->getPosition($app),$originalPositions)];
            $userClonedWeight = $clonedWeights[array_search($firmUser->getWeight($app),$originalWeights)];

            $userClonedWeight
                ->setPosition($userClonedPosition);

            $em->persist($userClonedWeight);
            $em->flush();

            $clonedFirmUser = clone $firmUser;

            // We change user email address to avoid creating duplicates
            $oldEmail = $firmUser->getEmail();
            $prefix = explode("@", $oldEmail)[0].'_dev';
            $suffix = explode("@", $oldEmail)[1];
            $newEmail = implode("@",[$prefix,$suffix]);

            $clonedFirmUser->setOrgId($clonedFirm->getId())
                ->setDptId($userClonedDepartment->getId())
                ->setPosId($userClonedPosition->getId())
                ->setWgtId($userClonedWeight->getId())
                ->setEmail($newEmail)
                ->setLastConnected(null)
                ->setInserted(new \DateTime);
            $em->persist($clonedFirmUser);

            if($userClonedWeight->getUsrId() != null){
                $em->flush();
                $userClonedWeight->setUsrId($clonedFirmUser->getId());
                $em->persist($userClonedWeight);
            }

            $clonedUsers[] = $clonedFirmUser;
            $originalUsers[] = $firmUser;
        }

        $extOrgs = [];
        foreach($firmExternalUsers as $firmExternalUser){

            $clonedFirmExternalUser = clone $firmExternalUser;
            $clonedExternalUsers[] = $clonedFirmExternalUser;
            $originalExternalUsers[] = $firmExternalUser;

            $assocIntUser = $firmExternalUser->getUser();

            $assocOrgIntUser = $repoO->findOneById($assocIntUser->getOrgId());

            if(!in_array($assocOrgIntUser, $extOrgs)){

                $clonedAssocOrgIntUser = clone $assocOrgIntUser;
                $clonedAssocOrgIntUser->setMasterUserId(0)->setValidated(new \DateTime);

                $orgClient = $repoOC->findOneBy(['organization' => $firm, 'clientOrganization' => $assocOrgIntUser]);
                $clonedOrgClient = clone $orgClient;
                $clonedOrgClient->setOrganization($clonedFirm)
                    ->setClientOrganization($clonedAssocOrgIntUser);
                $em->persist($clonedOrgClient);
                $em->persist($clonedAssocOrgIntUser);
                $em->flush();
                $clonedExtOrgs[] = $clonedAssocOrgIntUser;
                $extOrgs[] = $assocOrgIntUser;
            } else {
                $clonedAssocOrgIntUser = $clonedExtOrgs[array_search($assocOrgIntUser,$extOrgs)];
            }

            $clonedAssocIntUser = clone $assocIntUser;
            $clonedAssocIntUser->setOrgId($clonedAssocOrgIntUser->getId())
                ->setPosId(null)
                ->setDptId(null)
                ->setWgtId(null);
            $em->persist($clonedAssocIntUser);

            $clonedUsers[] = $clonedAssocIntUser;
            $originalUsers[] = $assocIntUser;

            $clonedFirmExternalUser
                ->setUser($clonedUsers[array_search($firmExternalUser->getUser(),$originalUsers)])
                ->setOrganization($clonedFirm);

            $em->persist($clonedFirmExternalUser);

            if($assocOrgIntUser->getMasterUserId() == 0){
                $em->flush();
                $assocOrgIntUser->getMasterUserId($clonedAssocIntUser->getId());
                $em->persist($assocOrgIntUser);
                $em->flush();
            }

        }

        $em->flush();

        $clonedFirm->setMasterUserId($clonedUsers[array_search($repoU->findOneById($firm->getMasterUserId()),$originalUsers)]->getId());

        $firmTeams = $repoT->findByOrganization($firm);

        foreach($firmTeams as $team){
            $clonedTeam = clone $team;
            $clonedFirm->addTeam($clonedTeam);
            $clonedTeams[] = $clonedTeam;
            $originalTeams[] = $team;
            foreach($team->getMembers() as $member){
                $clonedTeamUser = clone $member;
                $clonedTeamUser->setUsrId($clonedUsers[array_search($repoU->findOneById($member->getUsrId()),$originalUsers)]->getId());
                $clonedTeam->addMember($clonedTeamUser);
                $clonedTeamUsers[] = $clonedTeamUser;
                $originalTeamUsers[] = $member;
            }
        }

        $em->persist($clonedFirm);
        $em->flush();

        // Duplicate activity elements

        $firmActivities = $repoA->findByOrganization($firm);
        foreach($firmActivities as $key => $activity){
            $clonedActivity = clone $activity;
            $clonedFirm->addActivity($clonedActivity);
            $clonedActivities[] = $clonedActivity;
            $originalActivities[] = $activity;

            foreach($activity->getStages() as $stage){
                $clonedStage = clone $stage;
                $clonedActivity->addStage($clonedStage);
                $clonedStages[] = $clonedStage;
                $originalStages[] = $stage;

                foreach($stage->getCriteria() as $criterion){
                    $clonedCriterion = clone $criterion;
                    $clonedStage->addCriterion($clonedCriterion)
                        ->setMasterUserId($clonedUsers[array_search($stage->getMasterUser(),$originalUsers)]->getId());
                    $clonedCriteria[] = $clonedCriterion;
                    $originalCriteria[] = $criterion;

                    foreach($criterion->getParticipants() as $participant){
                        $clonedParticipant = clone $participant;
                        $clonedParticipant->setActivity($clonedActivity)
                            ->setStage($clonedStage)
                            ->setUsrId($clonedUsers[array_search($participant->getUserId(),$originalUsers)]->getId());
                        if($participant->getTeam() != null){
                            $clonedParticipant->setTeam($clonedTeams[array_search($participant->getTeam(),$originalTeams)]);
                        }
                        $clonedCriterion->addParticipant($clonedParticipant);
                        $clonedParticipants[] = $clonedParticipant;
                        $originalParticipants[] = $participant;

                        foreach($participant->getGrades() as $grade){
                            $clonedGrade = clone $grade;
                            $clonedGrade
                                ->setCriterion($clonedCriterion)
                                ->setStage($clonedStage)
                                ->setActivity($clonedActivity);
                            $clonedParticipant->addGrade($clonedGrade);
                            if($grade->getTeam() != null){
                                $clonedGrade->setTeam($clonedTeams[array_search($grade->getTeam(),$originalTeams)]);
                            }
                            if($grade->getGradedUsrId() != null){
                                $clonedGrade->setGradedUsrId($clonedUsers[array_search($repoU->findOneById($grade->getGradedUsrId()),$originalUsers)]->getId());
                            }
                            if($grade->getGradedTeaId() != null){
                                $clonedGrade->setGradedTeaId($clonedTeams[array_search($repoT->findOneById($grade->getGradedTeaId()),$originalTeams)]->getId());
                            }
                            $clonedGrades[] = $clonedGrade;
                            $originalGrades[] = $grade;
                        }
                    }
                }
            }

            // Duplicate activity results & rankings

            foreach($activity->getResults() as $result){
                $clonedResult = clone $result;
                if($result->getStage() != null){
                    $clonedResult->setStage($clonedStages[array_search($result->getStage(),$originalStages)]);
                }
                if($result->getCriterion() != null){
                    $clonedResult->setCriterion($clonedCriteria[array_search($result->getCriterion(),$originalCriteria)]);
                }
                if($result->getUsrId() != null){
                    $clonedResult->setUsrId($clonedUsers[array_search($repoU->findOneById($result->getUsrId()),$originalUsers)]->getId());
                }
                $clonedActivity->addResult($clonedResult);
                $clonedResults[] = $clonedResult;
                $originalResults[] = $result;
            }

            foreach($activity->getResultTeams() as $resultTeam){
                $clonedResultTeam = clone $resultTeam;
                if($resultTeam->getStage() != null){
                    $clonedResultTeam->setStage($clonedStages[array_search($resultTeam->getStage(),$originalStages)]);
                }
                if($resultTeam->getCriterion() != null){
                    $clonedResultTeam->setCriterion($clonedCriteria[array_search($resultTeam->getCriterion(),$originalCriteria)]);
                }
                if($resultTeam->getTeam() != null){
                    $clonedResultTeam->setTeam($clonedTeams[array_search($resultTeam->getTeam(),$originalTeams)]);
                }
                $clonedActivity->addResultTeam($clonedResultTeam);
                $clonedResultTeams[] = $clonedResultTeam;
                $originalResultTeams[] = $resultTeam;
            }

            foreach($activity->getRankings() as $ranking){
                $clonedRanking = clone $ranking;
                if($ranking->getStage() != null){
                    $clonedRanking->setStage($clonedStages[array_search($ranking->getStage(),$originalStages)]);
                }
                if($result->getCriterion() != null){
                    $clonedRanking->setCriterion($clonedCriteria[array_search($ranking->getCriterion(),$originalCriteria)]);
                }
                if($result->getUsrId() != null){
                    $clonedRanking->setUsrId($clonedUsers[array_search($repoU->findOneById($ranking->getUsrId()),$originalUsers)]->getId());
                }
                $clonedActivity->addRanking($clonedRanking);
            }

            foreach($activity->getHistoricalRankings() as $hRanking){
                $clonedHRanking = clone $hRanking;
                if($hRanking->getStage() != null){
                    $clonedHRanking->setStage($clonedStages[array_search($hRanking->getStage(),$originalStages)]);
                }
                if($hRanking->getCriterion() != null){
                    $clonedHRanking->setCriterion($clonedCriteria[array_search($hRanking->getCriterion(),$originalCriteria)]);
                }
                if($hRanking->getUsrId() != null){
                    $clonedHRanking->setUsrId($clonedUsers[array_search($repoU->findOneById($hRanking->getUsrId()),$originalUsers)]->getId());
                }
                $clonedActivity->addHistoricalRanking($clonedHRanking);
            }

            foreach($activity->getRankingTeams() as $rankingTeam){
                $clonedRankingTeam = clone $rankingTeam;
                if($rankingTeam->getStage() != null){
                    $clonedRankingTeam->setStage($clonedStages[array_search($rankingTeam->getStage(),$originalStages)]);
                }
                if($resultTeam->getCriterion() != null){
                    $clonedRankingTeam->setCriterion($clonedCriteria[array_search($rankingTeam->getCriterion(),$originalCriteria)]);
                }
                if($resultTeam->getTeam() != null){
                    $clonedRankingTeam->setTeam($clonedTeams[array_search($rankingTeam->getTeam(),$originalTeams)]);
                }
                $clonedActivity->addRankingTeam($clonedRankingTeam);
            }

            foreach($activity->getHistoricalRankingTeams() as $hRankingTeam){
                $clonedHRankingTeam = clone $hRankingTeam;
                if($hRankingTeam->getStage() != null){
                    $clonedHRankingTeam->setStage($clonedStages[array_search($hRankingTeam->getStage(),$originalStages)]);
                }
                if($hRankingTeam->getCriterion() != null){
                    $clonedHRankingTeam->setCriterion($clonedCriteria[array_search($hRankingTeam->getCriterion(),$originalCriteria)]);
                }
                if($hRankingTeam->getTeam() != null){
                    $clonedHRankingTeam->setTeam($clonedTeams[array_search($hRankingTeam->getTeam(),$originalTeams)]);
                }
                $clonedActivity->addHistoricalRankingTeam($clonedHRankingTeam);
            }

            if($key % 10 == 0){
                $em->persist($clonedFirm);
                $em->flush();
            }

        }

        $em->persist($clonedFirm);
        $em->flush();

        return $this->redirectToRoute('massiveUpdateOrganization', ['orgId' => $clonedFirm->getId()]);

    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $orgId
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/settings/organization/{orgId}/mupdate", name="massiveUpdateOrganization")
     */
    public function massiveUpdateOrganizationAction(Request $request, $orgId){

        $em = $this->em;
        $repoO = $em->getRepository(Organization::class);
        $organization = $repoO->findOneById($orgId);
        
        $organizationUsersForm = $this->createForm(UpdateOrganizationForm::class, $organization, ['standalone' =>true,'organization' => $organization,'app'=> $app]);
        $organizationUsersForm->handleRequest($request);

        if($organizationUsersForm->isValid()){

            foreach($organizationUsersForm->get('orgUsers')->getData() as $key => $submittedUser){

                    if($submittedUser->getPassword() != null){
                        $encoder = $app['security.encoder_factory']->getEncoder($submittedUser);
                        $submittedUser->setPassword($encoder->encodePassword($submittedUser->getPassword(), 'azerty'));
                    } else {
                        $consideredUser = $organization->getOrgUsers()[$key];
                        $submittedUser->setPassword($consideredUser->getPassword());
                    }
                    $em->persist($submittedUser);
            }

            foreach($organizationUsersForm->get('orgExtUsers')->getData() as $key => $submittedUser){
                $extUserFirstName = $submittedUser->getFirstname();
                $extUserLastName = $submittedUser->getLastname();
                $assocIntUser = $organization->getOrgExtUsers()[$key]->getUser();
                $assocIntUser->setFirstname($extUserFirstName)
                    ->setLastname($extUserLastName);

                $em->persist($assocIntUser);
            }
            $em->flush();
            foreach ($repoO->findAll() as $organization) {
                $organizations[] = $organization->toArray($app);
            }
            return $this->render('organization_list.html.twig',['organizations' => $organizations]);
            //return $this->redirectToRoute('manageOrganizations');

        }

        return $this->render('organization_massive_update.html.twig',
        [
            'form' => $organizationUsersForm->createView(),
            'organization' => $organization,
        ]);

    }

    public function displayWorkerElmts(Request $request, $currentPage = 1, $limit = 100){

        $em = $this->em;
        $repoWE = $em->getRepository(WorkerExperience::class);
        
        $searchWorkerForm = $this->createForm(SearchWorkerForm::class, null,['app' => $app]);
        $validateFirmForm = $this->createForm(ValidateFirmForm::class, null, ['standalone' => true]);
        $validateMailForm = $this->createForm(ValidateMailForm::class, null, ['standalone' => true]);
        $sendMailProspectForm = $this->createForm(SendMailProspectForm::class, null, ['standalone' => true]);
        $searchWorkerForm->handleRequest($request);

        $validateMassFirmForm = $this->createForm(ValidateMassFirmForm::class, null, ['standalone' => true, 'firms' => $searchedWorkerFirms]);
            $validateMassFirmForm->handleRequest($request);

        return $this->render('worker_search.html.twig',
        [
            'form' => $searchWorkerForm->createView(),
            'validateFirmForm' => $validateFirmForm->createView(),
            'validateMailForm' => $validateMailForm->createView(),
            'sendMailProspectForm' => $sendMailProspectForm->createView(),
            'validateMassFirmForm' =>  $validateMassFirmForm->createView(),
            'wfIdsSeq' => $workerFirmIdsSequence,
        ]);

        //return new JsonResponse(['searchedWorkers' => $searchedWorkers],200);

    }

    /**
     * @param Request $request
     * @param Application $app
     * @param int $currentPage
     * @param int $limit
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @Route("/workers/search", name="findWorkerElmts")
     */
    public function findWorkerElmts(Request $request, $currentPage = 1, $limit = 500){

        $em = $this->em;
        $repoWE = $em->getRepository(WorkerExperience::class);
        
        $searchWorkerForm = $this->createForm(SearchWorkerForm::class, null,['app' => $app]);
        $validateFirmForm = $this->createForm(ValidateFirmForm::class, null, ['standalone' => true]);
        $validateMailForm = $this->createForm(ValidateMailForm::class, null, ['standalone' => true]);
        $sendMailProspectForm = $this->createForm(SendMailProspectForm::class, null, ['standalone' => true]);
        $searchWorkerForm->handleRequest($request);
        $searchedWorkerFirms = null;
        $searchedWorkerIndividuals = null;
        $searchedWorkerExperiences = null;
        $isSearchByLocation = false;
        $workerFirmIdsSequence = '0';
        if($searchWorkerForm->get('fullName')->getData() === '' && $searchWorkerForm->get('firmName')->getData() === '' && $searchWorkerForm->get('position')->getData() === ''){
            $searchWorkerForm->get('submit')->addError(new FormError('There must be at least one filled criterion to look for'));
        }
        if($searchWorkerForm->isValid()){

            $qb = $em->createQueryBuilder();

            if($searchWorkerForm->get('position')->getData() != ''){



                $qb->select('we')
                ->from('App\Entity\WorkerExperience','we')
                ->innerJoin('App\Entity\WorkerFirm', 'wf', 'WITH', 'we.firm = wf.id')
                ->innerJoin('App\Entity\WorkerIndividual', 'wi', 'WITH', 'we.individual = wi.id')
                //->where('au.status = 4')
                ->where('we.position LIKE :position')
                ->andWhere('wf.name LIKE :firm')
                ->andWhere('wi.fullName LIKE :indiv');
                if($searchWorkerForm->get('currentOnly')->getData() == 1){
                    $qb->andWhere('we.active = 1');
                }
                if($searchWorkerForm->get('HQLocation')->getData() != ''){
                    $qb->andWhere('wf.HQLocation LIKE :HQLocation');
                }
                if($searchWorkerForm->get('country')->getData() != ''){
                    $qb->andWhere('wf.country = :country');
                }

                if($searchWorkerForm->get('state')->getData() != ''){
                    $qb->andWhere('wf.state = :state');
                }

                if($searchWorkerForm->get('city')->getData() != ''){
                    $qb->andWhere('wf.city = :city');
                }

                $qb->setParameter('position', '%'.$searchWorkerForm->get('position')->getData().'%')
                ->setParameter('firm', '%'.$searchWorkerForm->get('firmName')->getData().'%')
                ->setParameter('indiv', '%'.$searchWorkerForm->get('fullName')->getData().'%');


                if($searchWorkerForm->get('country')->getData() != ''){
                    $qb->setParameter('country',$searchWorkerForm->get('country')->getData());
                }

                if($searchWorkerForm->get('state')->getData() != ''){
                    $qb->setParameter('state', $searchWorkerForm->get('state')->getData());
                }

                if($searchWorkerForm->get('city')->getData() != ''){
                    $qb->setParameter('city', $searchWorkerForm->get('city')->getData());
                }

                if($searchWorkerForm->get('HQLocation')->getData() != ''){
                    $qb->setParameter('HQLocation', '%'.$searchWorkerForm->get('HQLocation')->getData().'%');
                }

                $qb2 = $em->createQueryBuilder();
                $qb2->select('count(we.id)')
                ->from('App\Entity\WorkerExperience','we')
                ->innerJoin('App\Entity\WorkerFirm', 'wf', 'WITH', 'we.firm = wf.id')
                ->innerJoin('App\Entity\WorkerIndividual', 'wi', 'WITH', 'we.individual = wi.id')
                //->where('au.status = 4')
                ->where('we.position LIKE :position')
                ->andWhere('wf.name LIKE :firm')
                ->andWhere('wi.fullName LIKE :indiv');


                if($searchWorkerForm->get('currentOnly')->getData() == 1){
                    $qb2->andWhere('we.active = 1');
                }
                if($searchWorkerForm->get('HQLocation')->getData() != ''){
                    $qb->andWhere('wf.HQLocation LIKE :HQLocation');
                }
                if($searchWorkerForm->get('country')->getData() != ''){
                    $qb->andWhere('wf.country = :country');
                }

                if($searchWorkerForm->get('state')->getData() != ''){
                    $qb->andWhere('wf.state = :state');
                }

                if($searchWorkerForm->get('city')->getData() != ''){
                    $qb->andWhere('wf.city = :city');
                }

                $qb2->setParameter('position', '%'.$searchWorkerForm->get('position')->getData().'%')
                ->setParameter('firm', '%'.$searchWorkerForm->get('firmName')->getData().'%')
                ->setParameter('indiv', '%'.$searchWorkerForm->get('fullName')->getData().'%');

                if($searchWorkerForm->get('country')->getData() != ''){
                    $qb->setParameter('country',$searchWorkerForm->get('country')->getData());
                }

                if($searchWorkerForm->get('state')->getData() != ''){
                    $qb->setParameter('state', $searchWorkerForm->get('state')->getData());
                }

                if($searchWorkerForm->get('city')->getData() != ''){
                    $qb->setParameter('city', $searchWorkerForm->get('city')->getData());
                }

                if($searchWorkerForm->get('HQLocation')->getData() != ''){
                    $qb->setParameter('HQLocation', '%'.$searchWorkerForm->get('HQLocation')->getData().'%');
                }


                $count = $qb2->getQuery()->getSingleScalarResult();

                //->setParameter('expTypes', $searchWorkerForm->get('currentOnly')->getData() == 1 ? '[true]' : '[false,true]');



                $searchedWorkerExperiences = new ArrayCollection(
                    $qb->setFirstResult($limit * ($currentPage - 1))
                        ->setMaxResults($limit)
                        ->getQuery()->getResult()
                );


                $iterator = $searchedWorkerExperiences->getIterator();

                $iterator->uasort(function ($first, $second) {
                    return ($first->getIndividual()->getExperiences()->last()->getStartDate() > $second->getIndividual()->getExperiences()->last()->getStartDate()) ? 1 : -1;
                });

                $searchedWorkerExperiences = new ArrayCollection(iterator_to_array($iterator));

            }

            /*if($searchWorkerForm->get('fullName')->getData() == '' && $searchWorkerForm->get('position')->getData() == '' && $searchWorkerForm->get('firmName')->getData() != ''){

                $searchedWorkerFirms = new ArrayCollection($qb->select('wf')
                ->from('App\Entity\WorkerFirm', 'wf')
                ->where('wf.name LIKE :firmName')
                ->setParameter('firmName', '%'.$searchWorkerForm->get('firmName')->getData().'%')
                ->getQuery()
                ->getResult());

                $iterator = $searchedWorkerFirms->getIterator();

                $iterator->uasort(function ($first, $second) {
                    return (count($first->getActiveExperiences()) < count($second->getActiveExperiences())) ? 1 : -1;
                });

                $searchedWorkerFirms = new ArrayCollection(iterator_to_array($iterator));



            }*/

            if($searchWorkerForm->get('fullName')->getData() != '' && $searchWorkerForm->get('position')->getData() == '' && $searchWorkerForm->get('firmName')->getData() == ''){

                $searchedWorkerIndividuals = new ArrayCollection($qb->select('wi')
                ->from('App\Entity\WorkerIndividual', 'wi')
                ->where('wi.fullName LIKE :fullName')
                ->setParameter('fullName', '%'.$searchWorkerForm->get('fullName')->getData().'%')
                ->getQuery()
                ->getResult());

                $iterator = $searchedWorkerIndividuals->getIterator();

                $iterator->uasort(function ($first, $second) {
                    if(count($first->getExperiences()) == 0 || count($second->getExperiences()) == 0 ){
                        return -1;
                    } else {
                        return ($first->getExperiences()->last()->getStartDate() > $second->getExperiences()->last()->getStartDate()) ? 1 : -1;
                    }
                });

                $searchedWorkerIndividuals = new ArrayCollection(iterator_to_array($iterator));

            }

            if($searchWorkerForm->get('fullName')->getData() == '' && $searchWorkerForm->get('position')->getData() == ''){

                $qb->select('wf')
                ->from('App\Entity\WorkerFirm', 'wf')
                ->where('wf.name LIKE :firmName');




                if($searchWorkerForm->get('country')->getData() != ''){
                    $qb->andWhere('wf.country = :country');
                }

                if($searchWorkerForm->get('state')->getData() != ''){
                    $qb->andWhere('wf.state = :state');
                }

                if($searchWorkerForm->get('city')->getData() != ''){
                    $qb->andWhere('wf.city = :city');
                }

                if($searchWorkerForm->get('HQLocation')->getData() != ''){
                    $qb->andWhere('wf.HQLocation LIKE :HQLocation');
                }

                if($searchWorkerForm->get('fSizeFrom')->getData() != -1){
                    if($searchWorkerForm->get('fSizeTo')->getData() == -1){
                        $qb->andWhere('wf.size >= :fSizeFrom AND wf.size IS NULL');
                    } else {
                        $qb->andWhere('wf.size BETWEEN :fSizeFrom AND :fSizeTo');
                    }

                } else {
                    if($searchWorkerForm->get('fSizeTo')->getData() != -1){
                        $qb->andWhere('wf.size <= :fSizeFrom AND wf.size IS NULL');
                    }
                }

                if($searchWorkerForm->get('fSector')->getData() != 0){
                    $qb->andWhere('wf.mainSector = :fSector');
                }

                if($searchWorkerForm->get('fType')->getData() != 99){
                    $qb->andWhere('wf.firmType = :fType');
                }

                $qb->orderBy('wf.nbLKEmployees','DESC');

                $qb->setParameter('firmName', '%'.$searchWorkerForm->get('firmName')->getData().'%');

                if($searchWorkerForm->get('fSizeFrom')->getData() != -1){
                    $qb->setParameter('fSizeFrom', $searchWorkerForm->get('fSizeFrom')->getData());
                }
                if($searchWorkerForm->get('fSizeTo')->getData() != -1){
                    $qb->setParameter('fSizeTo', $searchWorkerForm->get('fSizeTo')->getData());
                }

                if($searchWorkerForm->get('fSector')->getData() != 0){
                    $qb->setParameter('fSector', $searchWorkerForm->get('fSector')->getData());
                }

                if($searchWorkerForm->get('fType')->getData() != 99){
                    $qb->setParameter('fType', $searchWorkerForm->get('fType')->getData());
                }

                if($searchWorkerForm->get('country')->getData() != ''){
                    $qb->setParameter('country',$searchWorkerForm->get('country')->getData());
                }

                if($searchWorkerForm->get('state')->getData() != ''){
                    $qb->setParameter('state', $searchWorkerForm->get('state')->getData());
                }

                if($searchWorkerForm->get('city')->getData() != ''){
                    $qb->setParameter('city', $searchWorkerForm->get('city')->getData());
                }

                if($searchWorkerForm->get('HQLocation')->getData() != ''){
                    $qb->setParameter('HQLocation', '%'.$searchWorkerForm->get('HQLocation')->getData().'%');
                }

                $searchedWorkerFirms = new ArrayCollection($qb->getQuery()
                ->getResult());

                /*$iterator = $searchedWorkerFirms->getIterator();

                $iterator->uasort(function ($first, $second) {
                    return (count($first->getNbActiveExperiences()) < count($second->getNbActiveExperiences())) ? 1 : -1;
                });

                $searchedWorkerFirms = new ArrayCollection(iterator_to_array($iterator));*/
                $i = 0;
                foreach($searchedWorkerFirms as $searchedWorkerFirm){
                    if($i != 0){
                        $workerFirmIdsSequence .= '-'.$searchedWorkerFirm->getId();
                    } else {
                        $workerFirmIdsSequence = $searchedWorkerFirm->getId();
                    }
                    $i++;
                }



            }

            if($searchWorkerForm->get('HQLocation')->getData() != ''){
                $isSearchByLocation = true;
            }

        }

        $validateMassFirmForm = $this->createForm(ValidateMassFirmForm::class, null, ['standalone' => true, 'searchByLocation' => $isSearchByLocation, 'firms' => $searchedWorkerFirms]);
            $validateMassFirmForm->handleRequest($request);

        return $this->render('worker_search.html.twig',
        [
            'form' => $searchWorkerForm->createView(),
            'searchedWorkerIndividuals' => $searchedWorkerIndividuals,
            'searchedWorkerFirms' => $searchedWorkerFirms,
            'searchedWorkerExperiences' => $searchedWorkerExperiences,
            'validateFirmForm' => $validateFirmForm->createView(),
            'validateMailForm' => $validateMailForm->createView(),
            'sendMailProspectForm' => $sendMailProspectForm->createView(),
            'validateMassFirmForm' =>  $validateMassFirmForm->createView(),
            'wfIdsSeq' => $workerFirmIdsSequence,
            'searchByLocation' => $isSearchByLocation,
        ]);

        //return new JsonResponse(['searchedWorkers' => $searchedWorkers],200);

    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $couId
     * @return JsonResponse
     * @Route("/workers/states/{couId}", name="getStatesFromCountry")
     */
    public function getStatesFromCountry(Request $request, $couId){
        $em = $this->em;
        $repoC = $em->getRepository(Country::class);
        $repoS = $em->getRepository(State::class);
        $repoCI = $em->getRepository(City::class);
        if($couId != 0){
            $country = $repoC->findOneById($couId);
            $states = $repoS->findByCountry($country);
        } else {
            $states = $repoS->findAll();
        }
        $statesData = [];
        $citiesData = [];

        foreach($states as $state){

            $cities = $repoCI->findByState($state);
            foreach($cities as $city){
                $cityData = [];
                $cityData['value'] = $city->getId();
                $cityData['key'] = $city->getName();
                $citiesData[] = $cityData;
            }

            $stateData = [];
            $stateData['value'] = $state->getId();
            $stateData['key'] = $state->getName();
            $statesData[] = $stateData;
        }
        return new JsonResponse(['states' => $statesData,'cities' => $citiesData],200);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $staId
     * @return JsonResponse
     * @Route("/workers/cities/{staId}", name="getCitiesFromState")
     */
    public function getCitiesFromState(Request $request, $staId){
        $em = $this->em;
        $repoS = $em->getRepository(State::class);
        $repoC = $em->getRepository(City::class);
        if($staId != 0){
            $state = $repoS->findOneById($staId);
            $cities = $repoC->findByState($state);
        } else {
            $cities = $repoC->findAll();
        }
        $citiesData = [];
        foreach($cities as $city){
            $cityData = [];
            $cityData['value'] = $city->getId();
            $cityData['key'] = $city->getName();
            $citiesData[] = $cityData;
        }
        return new JsonResponse(['cities' => $citiesData],200);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $mid
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/validate-mail/{mid}", name="validateMailSent")
     */
    public function validateMailSent(Request $request, $mid){
        $em = $this->em;
        $repoM = $em->getRepository(Mail::class);
        $mail = $repoM->findOneById($mid);
        $mail->setRead(new \DateTime);
        $em->persist($mail);
        $em->flush();
        return new JsonResponse(['message' => 'success'],200);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $mid
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/deactivate-mail/{mid}", name="deactivateMail")
     */
    public function deactivateMail(Request $request, $mid){
        $em = $this->em;
        $repoM = $em->getRepository(Mail::class);
        $mail = $repoM->findOneById($mid);
        $workerIndividual = $mail->getWorkerIndividual();
        $workerIndividual->setGDPR(new \DateTime);
        $em->persist($workerIndividual);
        $em->flush();
        return new JsonResponse(['message' => 'success'],200);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $mid
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/delete-mail/{mid}", name="deleteMailSent")
     */
    public function deleteMailSent(Request $request, $mid){
        $em = $this->em;
        $repoM = $em->getRepository(Mail::class);
        $mail = $repoM->findOneById($mid);
        $em->remove($mail);
        $em->flush();
        return new JsonResponse(['message' => 'success'],200);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/workers/firm/create", name="createWorkerFirm")
     */
    public function createWorkerFirm(Request $request){
        $em = $this->em;
        $name = $request->get('name');
        $workerFirm = new WorkerFirm;
        $workerFirm->setName($name)->setActive(true);
        $em->persist($workerFirm);
        $em->flush();
        return new JsonResponse(['wfId' => $workerFirm->getId()],200);

    }


    /**
     * @param Request $request
     * @param Application $app
     * @param $wfId
     * @return RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/workers/firms/update/{wfiId}", name="updateWorkerFirm")
     */
    public function updateWorkerFirm(Request $request, $wfiId)
    {
        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }
        $em = $this->em;
        $repoWF = $em->getRepository(WorkerFirm::class);

        $workerFirm = $repoWF->findOneById($wfiId);
        if($workerFirm == null){
            $workerFirm = new WorkerFirm;
        }
        
        $updateWorkerFirmForm = $this->createForm(UpdateWorkerFirmForm::class, $workerFirm, ['standalone' => true]);
        $updateWorkerFirmForm->handleRequest($request);

        if($updateWorkerFirmForm->isSubmitted() && $updateWorkerFirmForm->isValid()){

            $repoWFS = $em->getRepository(WorkerFirmSector::class);
            $repoCO = $em->getRepository(Country::class);
            $repoCI = $em->getRepository(City::class);
            $repoS = $em->getRepository(State::class);
            /** @var Country */
            $country = $workerFirm->getCountry();
            $state = null;
            $city = null;

            if($country != null){
                $workerFirm->setHQCountry($country->getAbbr());
            }

            if($updateWorkerFirmForm->get('HQState')->getData() != null){
                $state = $repoS->findOneByName($updateWorkerFirmForm->get('HQState')->getData());
                if($state == null){
                    $state = new State;
                    $state->setCountry($country)->setName($updateWorkerFirmForm->get('HQState')->getData())->setCreatedBy($currentUser->getId());
                    $em->persist($state);
                }
            }

            if($updateWorkerFirmForm->get('HQCity')->getData() != null)
            $city = $repoCI->findOneByName($updateWorkerFirmForm->get('HQCity')->getData());
            if($city == null){
                $city = new City;
                $city->setCountry($country)->setState($state)->setName($updateWorkerFirmForm->get('HQCity')->getData())->setCreatedBy($currentUser->getId());
                $em->persist($city);
            }

            $workerFirm
                ->setCountry($country)
                ->setState($state)
                ->setCity($city);

            if($wfiId == 0){
                $workerFirm->setCreated(1)->setName($updateWorkerFirmForm->get('commonName')->getData());
            }

            $em->persist($workerFirm);
            $em->flush();

            return $this->redirectToRoute('manageWorkerFirms');
        }

        return $this->render('worker_firm_data.html.twig',
        [
            'form' => $updateWorkerFirmForm->createView(),
            'wFirm' => $workerFirm,
        ]);

    }

    /**
     * @param Request $request
     * @param Application $app
     * @return JsonResponse
     * @Route("/workers/participants/search", name="dynamicSearchParticipant")
     */
    public function dynamicSearchParticipant(Request $request){

        $name = $request->get('name');
        $em = $this->em;
        $qb = $em->createQueryBuilder();
        $type = $request->get('type');

        
        $user = $this->user;
        $organization = $user ? $this->org : null;
        $orgId = $organization->getId();
        $clients = $organization ? $organization->getClients() : new ArrayCollection();
        $repoP = $em->getRepository(Participation::class);
        
        /*
        $clientIds = '';
        foreach ($clients as $client) {
            $clientIds .= "'". $client->getId() . "', ";
        }
        $clientIds = substr($clientIds,0,-2);*/
    

        $participants = $request->get('p') ?: [];
        $partUsers = [0];
        $partTeams = [0];
        $partFirms = [0];
        $partExtUsers = [0];

        foreach ($participants as $participant) {
            if(isset($participant['el'])){
                switch($participant['el']){
                    case 'u':
                        $partUsers[] = $participant['id'];
                        break;
                    case 't':
                        $partTeams[] = $participant['id'];
                        break;
                    case 'f':
                        $partFirms[] = $participant['id'];
                        break;
                    case 'eu':
                        $partExtUsers[] = $participant['id'];
                        break;
                }
            } else {
                $participant = $repoP->find($participant['id']);
                $externalUser = $participant->getExternalUser();
                if($participant->getTeam()){
                    $partTeams[] = $participant->getTeam()->getId();
                } else if ($externalUser) {
                    $partExtUsers[] = $externalUser->getId();
                    if($externalUser->isSynthetic()){
                        $partFirms[] = $externalUser->getClient()->getWorkerFirm()->getId();
                    }
                } else {
                    $partUsers[] = $participant->getUser()->getId();
                }
            }

        }

        //return new JsonResponse($partExtUsers,200);


        if($type == 'all'){
            //$elements = new ArrayCollection($qb->select('wf.name AS orgName','wf.id AS wfiId','wf.logo','identity(wf.organization) AS orgId', 'u.id AS usrId', 'u.username', 'u.picture AS usrPicture', 'eu.id AS extUsrId', 't.id AS teaId', 't.name AS teaName', 't.picture AS teaPicture')
            $elementsUsrTeams = $qb->select('u.id AS usrId', 'u.username', 'u.picture AS usrPicture'/*, 'eu.id AS extUsrId'*/, 't.id AS teaId', 't.name AS teaName', 't.picture AS teaPicture')
            ->from('App\Entity\User', 'u')
            ->leftJoin('App\Entity\Team', 't', 'WITH', 't.organization = u.organization')
            ->where('u.organization = :oid')    
            ->andWhere('u.username LIKE :name AND u.id NOT IN (:partUsers)')
            ->orWhere('t.name LIKE :name AND t.id NOT IN (:partTeams)')
            ->setParameter('name', '%'. $name .'%')
            ->setParameter('partUsers', $partUsers)
            ->setParameter('partTeams', $partTeams)
            ->setParameter('oid', $organization)
            ->getQuery()
            ->getResult();

            $qb2 = $em->createQueryBuilder();
            $elementsExtUsers = $qb2->select('wf.name AS orgName', 'wf.id AS wfiId','wf.logo', 'o.id AS orgId', 'CONCAT(eu.firstname,\' \', eu.lastname) AS username', 'eu.lastname AS l', 'eu.synthetic AS s', 'eu.id AS extUsrId, u.picture AS usrPicture, u.id AS usrId')
                ->from('App\Entity\ExternalUser', 'eu')
                ->innerJoin('App\Entity\User', 'u', 'WITH', 'u.id = eu.user')
                ->innerJoin('App\Entity\Client', 'c', 'WITH', 'c.id = eu.client')
                ->innerJoin('App\Entity\Organization', 'o', 'WITH', 'o.id = c.clientOrganization')
                ->innerJoin('App\Entity\WorkerFirm', 'wf', 'WITH', 'wf.id = o.workerFirm')
                ->where('CONCAT(eu.firstname,\' \', eu.lastname) LIKE :name AND eu.synthetic IS NULL OR eu.lastname LIKE :name AND eu.synthetic = TRUE')
                ->andWhere('eu.id NOT IN (:partExtUsers)')
                ->andWhere('eu.client IN (:clients)')
                ->setParameter('partExtUsers',$partExtUsers)
                ->setParameter('name', '%'. $name .'%')
                ->setParameter('clients', $clients)
                ->getQuery()
                ->getResult();
            
            $arrangedExtUsers = [];
            foreach($elementsExtUsers as $elementsExtUser){
                $arrangedExtUser = $elementsExtUser;
                if($elementsExtUser['s']){
                    $arrangedExtUser['username'] = $elementsExtUser['l'];
                }
                $arrangedExtUser['l'] = "";
                $arrangedExtUsers[] = $arrangedExtUser;
            }



            $qb3 = $em->createQueryBuilder();
            $elementsFirms = $qb3->select('wf.name AS orgName','wf.id AS wfiId','wf.logo', 'o.id AS orgId', 'c.id AS cliId')
            ->from('App\Entity\WorkerFirm', 'wf')
            // Join is made to prevent selection of clients
            ->leftJoin('App\Entity\Organization', 'o', 'WITH', 'o.workerFirm = wf.id')
            ->leftJoin('App\Entity\Client', 'c', 'WITH', 'c.clientOrganization = o.id')
            ->where('wf.name LIKE :name')
            ->andWhere('wf.id NOT IN (:partFirms)')
            // Line below prevents selection of firm which is already client (existing as synth ext user), and user self firm mirrored as client for its clients
            ->andWhere('c.organization IS NULL OR (c.organization != :oid AND c.clientOrganization != :oid)')
            ->setParameter('name', '%'. $name .'%')
            ->setParameter('oid', $organization)
            ->setParameter('partFirms',$partFirms)
            ->getQuery()
            ->getResult();

            $elements = new ArrayCollection(array_merge($elementsUsrTeams,$arrangedExtUsers,$elementsFirms));


        } else {

            $elements = new ArrayCollection(
                $qb->select('wf.name AS orgName','wf.id AS wfiId','wf.logo','o.id AS orgId', 'c.id AS cliId', 'IDENTITY(c.organization) AS cOrg')
                    ->from('App\Entity\WorkerFirm', 'wf')
                    ->leftJoin('App\Entity\Organization', 'o', 'WITH', 'o.workerFirm = wf.id')
                    ->leftJoin('App\Entity\Client', 'c', 'WITH', 'c.clientOrganization = o.id')
                    ->where('wf.name LIKE :name')
                //->andWhere('c.organization = :org')
                    ->setParameter('name', '%'. $name .'%')
                //->setParameter('org', $organization)
                    ->getQuery()
                    ->getResult()
                );

            $elements = $elements->map(fn($e) => [
                'orgName' => $e['orgName'],
                'wfiId' => $e['wfiId'],
                'logo' => $e['logo'],
                'orgId' => $e['orgId'],
                'cliId' => $e['cOrg'] == $orgId ? $e['cliId'] : ""
            ]);
            
        }



        $qParts = [];
        foreach($elements as $element){
            if(isset($element['extUsrId'])){
                $element['e'] = 'eu';
            } else if(isset($element['usrId'])){
                $element['e'] = 'u';
            } else if(isset($element['teaId'])){
                $element['e'] = 't';
            } else {
                $element['e'] = 'f';
            }
            //if(!$element['username']){$element['usrId'] = "";}
            $qParts[] = $element;
        }

        //$workerFirms = array_combine($values,$keys);
        return new JsonResponse(['qParts' => $qParts],200);

    }
    
    /**
     * @param Request $request
     * @param Application $app
     * @return JsonResponse
     * @Route("/workers/firm/search", name="dynamicSearchParentFirm")
     */
    public function dynamicSearchParentFirm(Request $request){


        $firmName = $request->get('name');

        $em = $this->em;
        $repoWF = $em->getRepository(WorkerFirm::class);
        $qb = $em->createQueryBuilder();
        $firms = new ArrayCollection($qb->select('wf')
        ->from('App\Entity\WorkerFirm', 'wf')
        ->where('wf.name LIKE :firmName')
        ->andWhere('wf.active = true AND wf.nbActiveExp > 0 OR wf.organization IS NOT NULL')
        ->setParameter('firmName', '%'.$firmName.'%')
        ->orderBy('wf.nbActiveExp','DESC')
        ->getQuery()
        ->getResult());

        if(sizeof($firms) == 0){
            $firms = new ArrayCollection($qb/*->select('wf')
            ->from('App\Entity\WorkerFirm','wf')*/
            ->where('wf.name LIKE :firmName')
            ->andWhere('wf.active = true')
            ->setParameter('firmName', '%'.$firmName.'%')
            ->orderBy('wf.nbActiveExp','DESC')
            ->getQuery()
            ->getResult());
        }

        $workerFirms = [];
        foreach($firms as $firm){
            $workerFirm = ['id' => $firm->getId(), 'name' => $firm->getName(),'logo' => $firm->getLogo()];
            $workerFirms[] = $workerFirm;
        }

        //$workerFirms = array_combine($values,$keys);
        return new JsonResponse(['workerFirms' => $workerFirms],200);

    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $wfiId
     * @return JsonResponse
     * @Route("/workers/get-firm-from-id/{wfiId}", name="getFirmFromId")
     */
    public function getFirmFromId(Request $request, $wfiId){
        $em = $this->em;
        $repoWF = $em->getRepository(WorkerFirm::class);
        $firm = $repoWF->findOneById($wfiId);
        return new JsonResponse(['firmName' => $firm->getName()],200);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $wiId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/workers/individual/update/{wiId}", name="updateWorkerIndividual")
     */
    public function updateWorkerIndividual(Request $request, $wiId){

        $em = $this->em;
        $repoWI = $em->getRepository(WorkerIndividual::class);
        $repoWF = $em->getRepository(WorkerFirm::class);
        $workerIndividual = $repoWI->findOneById($wiId);
        
        $expFirm = null;
        $mailPrefix = null;
        $mailSuffix = null;
        if(count($workerIndividual->getExperiences()) > 0){
            $expFirm = $workerIndividual->getExperiences()->first()->getFirm();
            $mailPrefix = $expFirm->getMailPrefix();
            $mailSuffix = $expFirm->getMailSuffix();
        }
        $updateWorkerIndividualForm = $this->createForm(UpdateWorkerIndividualForm::class, $workerIndividual, ['workerIndividual' => $workerIndividual, 'mailPrefix' => $mailPrefix, 'mailSuffix' => $mailSuffix, 'standalone' => true]);
        $sendMailProspectForm = $this->createForm(SendMailProspectForm::class, null, ['standalone' => true]);
        $updateWorkerIndividualForm->handleRequest($request);
        if($updateWorkerIndividualForm->isSubmitted()){
            if($updateWorkerIndividualForm->isValid()){
                foreach($updateWorkerIndividualForm->get('experiences') as $key => $experienceForm){
                    $workerIndividual->getExperiences()->get($key)->setFirm($repoWF->findOneById((int) $experienceForm->get('firm')->getData()));
                    if($experienceForm->get('enddate')->getData() == null){
                        $workerIndividual->getExperiences()->get($key)->setEnddate(new \DateTime);
                    }
                }
                //die;

                $em->persist($workerIndividual);
                $em->flush();
                if($expFirm != null){
                    return $this->redirectToRoute('displayWorkerFirm',['wfId' => $expFirm->getId()]);
                } else {
                    return $this->redirectToRoute('findWorkerElmts');
                }
            } else {
                $errors = $this->buildErrorArray($updateWorkerIndividualForm);
                return $errors;
            }
        }

        return $this->render('worker_individual_data.html.twig',
        [
            'workerIndividual' => $workerIndividual,
            'form' => $updateWorkerIndividualForm->createView(),
            'sendMailForm' => $sendMailProspectForm->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $mailId
     * @return bool
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/lib/img/void1x1.png/{mailId}", name="setReadEmail")
     */
    public function setReadEmail(Request $request, $mailId){
        $em = $this->em;
        $repoM = $em->getRepository(Mail::class);
        $mail = $repoM->findOneBy($mailId);
        $mail->setRead(new \DateTime);
        $em->persist($mail);
        $em->flush();
        return true;
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $winId
     * @return JsonResponse
     * @Route("/workers/individual/send-prospect-mail/{winId}", name="sendProspectMail")
     */
    public function sendProspectMail(Request $request, $winId){
        $em = $this->em;
        $repoWI = $em->getRepository(WorkerIndividual::class);
        $workerIndividual = $repoWI->findOneById($winId);
        $firmLocation = $workerIndividual->getExperiences()->first()->getFirm()->getCountry()->getAbbr();
        
        $sendMailProspectForm = $this->createForm(SendMailProspectForm::class, null, ['standalone' => true]);
        $sendMailProspectForm->handleRequest($request);
        if($sendMailProspectForm->isValid()){
            $settings = [];
            $recipients = [];
            $recipients[] = $workerIndividual;
            $settings['location'] = $firmLocation;
            $settings['pType'] = $sendMailProspectForm->get('pType')->getData();
            $settings['language'] = $sendMailProspectForm->get('language')->getData();
            $settings['addPresFR'] = $sendMailProspectForm->get('addPresentationFR')->getData();
            $settings['addPresEN'] = $sendMailProspectForm->get('addPresentationEN')->getData();

            $this->forward('App\Controller\MailController::sendMail', ['recipients' => $recipients, 'settings' => $settings, 'actionType' => 'prospecting_1']);

            return new JsonResponse(['message' => 'success'],200);
        } else {
            $errors = $this->buildErrorArray($sendMailProspectForm);
            return $errors;
        }

    }

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     * @Route("/settings/mails", name="checkMails")
     */
    public function checkMails(Request $request){
        $em = $this->em;
        $repoM = $em->getRepository(Mail::class);
        $mails = $repoM->findBy([],['inserted' => 'DESC']);
        return $this->render('check_mails.html.twig',
        [
            'mails' => $mails,
            'app' => $app,
        ]);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $wfId
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/workers/individual/add/{wfId}", name="addWorkerFirmIndividual")
     */
    public function addWorkerFirmIndividual(Request $request, $wfId){
        $em = $this->em;
        $workerIndividual = new WorkerIndividual;
        $workerExperience = new WorkerExperience;
        $workerExperience->setActive(true);

        // We force the creation of a fictious experience

        $repoWF = $em->getRepository(WorkerFirm::class);
        $workerFirm = $repoWF->findOneById($wfId);
        $workerIndividual->setCreated(1)->addExperience($workerExperience);
        $workerFirm->addExperience($workerExperience);


        //return print_r($workerIndividual->getExperiences()->get(0)->getInserted());

        //$em->persist($workerExperience)
        //$em->flush()
        
        $workerIndividualForm = $this->createForm(UpdateWorkerIndividualForm::class, $workerIndividual, ['standalone' => true]);
        $sendMailProspectForm = $this->createForm(SendMailProspectForm::class, null, ['standalone' => true]);
        $sendMailProspectForm->handleRequest($request);
        $workerIndividualForm->handleRequest($request);
        if($workerIndividualForm->isSubmitted()){
            if($workerIndividualForm->isValid()){
                //$workerIndividualData = $workerIndividualForm->getData();
                $workerIndividual->setCreated(1);

                //$experiences = $workerIndividualForm->get('experiences')->getData();
                foreach($workerIndividualForm->get('experiences') as $key => $experienceForm){
                    //if(count($experiences) > 1){
                        $workerIndividual->getExperiences()->get($key)->setFirm($repoWF->findOneById((int) $experienceForm->get('firm')->getData()));
                    //} else {
                        //$experiences->setFirm($repoWF->findOneById((int) $experienceForm->get('firm')->getData()));
                    //}
                }
                $em->persist($workerIndividual);
                $em->persist($workerFirm);
                $em->flush();
                return $this->redirectToRoute('displayWorkerFirm',['wfId' => $wfId]);
            }
        }

        return $this->render('worker_individual_data.html.twig',
        [
            'form' => $workerIndividualForm->createView(),
            'sendMailForm' => $sendMailProspectForm->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/workers/individual/add", name="addWorkerIndividual")
     */
    public function addWorkerIndividual(Request $request){

        $em = $this->em;
        $workerIndividual = new WorkerIndividual;
        $repoWF = $em->getRepository(WorkerFirm::class);
        $workerExperience = new WorkerExperience;
        $workerExperience->setActive(true);
        $workerIndividual->setCreated(1)->addExperience($workerExperience);

        
        $workerIndividualForm = $this->createForm(UpdateWorkerIndividualForm::class, $workerIndividual, ['standalone' => true]);
        $sendMailProspectForm = $this->createForm(SendMailProspectForm::class, null, ['standalone' => true]);
        $sendMailProspectForm->handleRequest($request);
        $workerIndividualForm->handleRequest($request);
        if($workerIndividualForm->isSubmitted()){
            if($workerIndividualForm->isValid()){
                $workerIndividual = $workerIndividualForm->getData();
                $workerIndividual->setCreated(1);
                foreach($workerIndividualForm->get('experiences') as $key => $experienceForm){
                    $wfId = (int) $experienceForm->get('firm')->getData();
                    $workerIndividual->getExperiences()->get($key)->setFirm($repoWF->findOneById($wfId));
                }
                $em->persist($workerIndividual);
                $em->flush();
                return $this->redirectToRoute('displayWorkerFirm',['wfId' => $wfId]);
            }
        }

        return $this->render('worker_individual_data.html.twig',
        [
            'form' => $workerIndividualForm->createView(),
            'sendMailForm' => $sendMailProspectForm->createView(),
        ]);

    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $wiId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/workers/individual/delete/{wiId}", name="deleteWorkerIndividual")
     */
    public function deleteWorkerIndividual(Request $request, $wiId){

        $connectedUser = MasterController::getAuthorizedUser($app);
        if($connectedUser->getRole() != 4){
            return $this->render('errors/403.html.twig');
        }
        $em = $this->em;
        $repoWI = $em->getRepository(WorkerIndividual::class);
        $workerIndividual = $repoWI->findOneById($wiId);
        $em->remove($workerIndividual);
        $em->flush();
        return new JsonResponse(['message' => "Success"],200);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $wiId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/workers/individual/validate-mail/{wiId}", name="validateWorkerEmail")
     */
    public function validateWorkerEmail(Request $request, $wiId){
        $em = $this->em;
        $repoWI = $em->getRepository(WorkerIndividual::class);
        $workerIndividual = $repoWI->findOneById($wiId);
        
        $validateMailForm = $this->createForm(ValidateMailForm::class, $workerIndividual, ['standalone' => true]);
        $validateMailForm->handleRequest($request);

        if($validateMailForm->isValid()){
            $em->persist($workerIndividual);
            $em->flush();
            return new JsonResponse(['message' => 'success', 'email' => $workerIndividual->getEmail()],200);
        } else {
            $errors = $this->buildErrorArray($validateMailForm);
            return $errors;
        }

    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $wfId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/workers/firm/validate-mails/{wfId}", name="validateMassWorkerEmails")
     */
    public function validateMassWorkerEmails(Request $request, $wfId){
        $em = $this->em;

        
        $validateMassMailForm = $this->createForm(ValidateMassMailForm::class, null, ['standalone' => true]);
        $validateMassMailForm->handleRequest($request);

        if($validateMassMailForm->isValid()){
            $mails = [];
            $repoWF = $em->getRepository(WorkerFirm::class);
            $workerFirm = $repoWF->findOneById($wfId);
            $workingIndividuals = $workerFirm->getWorkingIndividuals();
            foreach($validateMassMailForm->get('workingIndividuals') as $key => $workingIndividualForm){
                $workingIndividualFormData = $workingIndividualForm->getData();
                $workingIndividual = $workingIndividuals->get($key);
                $workingIndividual->setFirstname($workingIndividualFormData->getFirstname())
                    ->setLastname($workingIndividualFormData->getLastname())
                    ->setEmail($workingIndividualFormData->getEmail())
                    ->setMale($workingIndividualFormData->isMale());
                $mails[] = $workingIndividualFormData->getEmail();
                $em->persist($workingIndividual);

            }
            $em->flush();
            return new JsonResponse(['message' => 'success', 'emails' => $mails],200);
        } else {
            $errors = $this->buildErrorArray($validateMassMailForm);
            return $errors;
        }

    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $orgId
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/organization/{orgId}/set-organization-to-criteria", name="setOrganizationToCriteriaAndStages")
     */
    public function setOrganizationToCriteriaAndStages(Request $request, $orgId){

        $em = $this->em;
        $repoC = $em->getRepository(Criterion::class);
        $repoO = $em->getRepository(Organization::class);
        $organization = $repoO->findOneById($orgId);
        $activities = $organization->getActivities();
        foreach($activities as $activity){
            foreach($activity->getStages() as $stage){
                $stage->setOrganization($organization);
                $em->persist($stage);
                foreach($stage->getCriteria() as $criterion){
                    $criterion->setOrganization($organization);
                    $em->persist($criterion);
                }
            }
        }
        $em->flush();
        return $this->redirectToRoute('manageOrganizations');
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $isSearchByLocation
     * @param $wfIdsSeq
     * @return JsonResponse|RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/workers/firm/validate-firms/{isSearchByLocation}/{wfIdsSeq}", name="validateMassFirm")
     */
    public function validateMassFirm(Request $request, $isSearchByLocation, $wfIdsSeq)
    {
        $currentUser = $this->user;
        if (!$currentUser instanceof User) {
            return $this->redirectToRoute('login');
        }

        

        $workerFirmIds = explode("-",$wfIdsSeq);
        $em = $this->em;
        $repoWF = $em->getRepository(WorkerFirm::class);
        $workerFirms = [];
        foreach($workerFirmIds as $workerFirmId){
            $workerFirms[] = $repoWF->findOneById($workerFirmId);
        }
        $validateMassFirmForm = $this->createForm(ValidateMassFirmForm::class, null, ['standalone' => true, 'searchByLocation' => $isSearchByLocation, 'firms' => $workerFirms]);
        $validateMassFirmForm->handleRequest($request);

        if($validateMassFirmForm->isValid()){

            $repoCO = $em->getRepository(Country::class);
            $repoCI = $em->getRepository(City::class);
            $repoS = $em->getRepository(State::class);

            foreach($validateMassFirmForm->get('firms') as $key => $workerFirmForm){

                $workerFirmFormData = $workerFirmForm->getData();
                //$workerFirm = $workerFirmForm->getData();
                $workerFirm = $workerFirms[$key];
                $country = null;
                $state = null;
                $city = null;


                $workerFirm->setCommonName($workerFirmFormData->getCommonName())
                    ->setMailPrefix($workerFirmFormData->getMailPrefix())
                    ->setMailSuffix($workerFirmFormData->getMailSuffix());

                //$workerFirm->setHQCountry($workerFirmFormData->getHQCountry());
                $country = $repoCO->findOneByAbbr($workerFirmFormData->getHQCountry());

                if($workerFirmFormData->getHQState() != ''){
                    $state = $repoS->findOneByName($workerFirmFormData->getHQState());
                    if($state == null){
                        $state = new State;
                        $state->setCountry($country)->setName($workerFirmFormData->getHQState())->setCreatedBy($currentUser->getId());
                        $em->persist($state);
                    }
                    //$workerFirm->setHQState($workerFirmFormData->getHQState());
                }

                if($workerFirmFormData->getHQCity() != ''){
                    $city = $repoCI->findOneByName($workerFirmFormData->getHQCity());
                    if($city == null){
                        $city = new City;
                        $city->setCountry($country)->setState($state)->setName($workerFirmFormData->getHQCity())->setCreatedBy($currentUser->getId());
                        $em->persist($city);
                    }
                    //$workerFirm->setHQCity($workerFirmFormData->getHQCity());
                }

                $workerFirm->setCountry($country)->setState($state)->setCity($city);
                $em->persist($workerFirm);

                if(!$isSearchByLocation && $validateMassFirmForm->get('createLocOtherFirms')->getData() == true){

                    $qb = $em->createQueryBuilder();
                    $qb->select('wf')
                        ->from('App\Entity\WorkerFirm', 'wf')
                        ->where('wf.HQLocation LIKE :HQLocation');

                    $qb->setParameter('HQLocation', '%'.$workerFirm->getHQCity().'%');



                    $firmsWithSameLocation = $qb->getQuery()->getResult();

                    foreach($firmsWithSameLocation as $firmWithSameLocation){
                        $firmWithSameLocation->setHQCity($workerFirm->getHQCity())
                        ->setHQState($workerFirm->getHQState())
                        ->setHQCountry($workerFirm->getHQCountry())
                        ->setCity($workerFirm->getCity())
                        ->setState($workerFirm->getState())
                        ->setCountry($workerFirm->getCountry());
                        $em->persist($firmWithSameLocation);
                    }
                }

                $em->flush();
            }

            return new JsonResponse(['message' => 'success'], 200);

        } else {
            $errors = $this->buildErrorArray($validateMassFirmForm);
            return $errors;
        }

    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $wfId
     * @return JsonResponse
     * @Route("/workers/firm/get-mailable-individuals/{wfId}", name="getMailableIndividualsFromFirm")
     */
    public function getMailableIndividualsFromFirm(Request $request, $wfId){
        $em = $this->em;
        $repoWF = $em->getRepository(WorkerFirm::class);
        $workerFirm = $repoWF->findOneById($wfId);
        $activeExperiences = $workerFirm->getActiveExperiences();
        $options = [];

        foreach($activeExperiences as $activeExperience){
            if($activeExperience->getIndividual()->getEmail() != null){
                $indiv = $activeExperience->getIndividual();
                $option['key'] = $indiv->getFullName().' ('.$activeExperience->getPosition().')';
                $option['email'] = $indiv->getEmail();
                $option['value'] = $indiv->getId();
                $options[] = $option;
            }
        }

        return new JsonResponse(['options' => $options,200]);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $wfId
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/workers/firm/validate/{wfId}", name="validateFirm")
     */
    public function validateFirm(Request $request, $wfId){
        $em = $this->em;
        $repoWF = $em->getRepository(WorkerFirm::class);
        $workerFirm = $repoWF->findOneById($wfId);
        
        $validateFirmForm = $this->createForm(ValidateFirmForm::class, $workerFirm, ['standalone' => true]);
        $validateFirmForm->handleRequest($request);

        if($validateFirmForm->isValid()){

            $workerFirmData = $validateFirmForm->getData();
            $em->persist($workerFirm);
            $em->flush();
            return new JsonResponse(['message' => 'success', 'firmMailPrefix' => $workerFirm->getMailPrefix(), 'firmMailSuffix' => $workerFirm->getMailSuffix()],200);
            } else {
            $errors = $this->buildErrorArray($validateFirmForm);
            return $errors;
        }

    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $wiId
     * @param $firstname
     * @param $lastname
     * @param $male
     * @param $wiEmail
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/workers/individual/validate-mail/{wiId}/{firstname}/{lastname}/{male}/{wiEmail}", name="validateWorkerEmailFromSelfPage")
     */
    public function validateWorkerEmailFromSelfPage(Request $request, $wiId, $firstname, $lastname, $male, $wiEmail){
        $em = $this->em;
        $repoWI = $em->getRepository(WorkerIndividual::class);
        if(!preg_match("/^(?:[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])*$/",$wiEmail)){
            $message = "Email is not correctly formatted, reconsider email address";
            $code = 500;
        } else if($repoWI->findOneByEmail($wiEmail) != null){
            $message = "There is already";
            $code = 500;
        } else {
            $message = "Related user has now a valid email address !";
            $code = 200;
            $workerIndividual = $repoWI->findOneById($wiId);
            $workerIndividual->setEmail($wiEmail)->setMale($male)->setFirstname($firstname)->setLastname($lastname);
            $em->persist($workerIndividual);
            $em->flush();
        }
        return new JsonResponse(['message' => $message],$code);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $wfId
     * @return mixed
     * @throws Exception
     * @Route("/workers/firm/{wfId}", name="displayWorkerFirm")
     */
    public function displayWorkerFirm(Request $request, $wfId){

        $em = $this->em;
        $repoWF = $em->getRepository(WorkerFirm::class);
        $repoWE = $em->getRepository(WorkerExperience::class);
        $wFirm = $repoWF->findOneById($wfId);
        $searchedIndividuals = [];
        $searchedFirmExperiences = $repoWE->findByFirm($wFirm);
        
        $validateMailForm = $this->createForm(ValidateMailForm::class, null, ['standalone' => true]);
        $sendMailProspectForm = $this->createForm(SendMailProspectForm::class, null, ['standalone' => true]);

        foreach($searchedFirmExperiences as $searchedFirmExperience){
            $searchedIndividuals[] = $searchedFirmExperience->getIndividual();
        }

        $searchedFirmIndividuals = new ArrayCollection(array_unique($searchedIndividuals));

        $firmActiveIndividuals = $searchedFirmIndividuals->filter(function(WorkerIndividual $individual) use ($wFirm) {
            return $individual->getExperiences()->first()->getFirm() == $wFirm;
        });
        $firmInactiveIndividuals = $searchedFirmIndividuals->filter(function(WorkerIndividual $individual) use ($wFirm) {
            return $individual->getExperiences()->first()->getFirm() != $wFirm;
        });


        $iterator = $firmActiveIndividuals->getIterator();

        $iterator->uasort(function ($first, $second) {
            return ($first->getExperiences()->last()->getStartDate() > $second->getExperiences()->last()->getStartDate()) ? 1 : -1;
        });

        $firmActiveIndividuals = new ArrayCollection(iterator_to_array($iterator));
        $validateMassMailForm = $this->createForm(ValidateMassMailForm::class, $wFirm, ['standalone' => true]);
        $validateMassMailForm->handleRequest($request);

        $iterator = $firmInactiveIndividuals->getIterator();

        $iterator->uasort(function ($first, $second) {
            return ($first->getExperiences()->last()->getStartDate() > $second->getExperiences()->last()->getStartDate()) ? 1 : -1;
        });

        $firmInactiveIndividuals = new ArrayCollection(iterator_to_array($iterator));

        return $this->render('worker_firm_elements.html.twig',
        [
            'wFirm' => $wFirm,
            'firmActiveIndividuals' => $firmActiveIndividuals,
            'firmInactiveIndividuals' => $firmInactiveIndividuals,
            'validateMailForm' => $validateMailForm->createView(),
            'sendMailProspectForm' => $sendMailProspectForm->createView(),
            'validateMassMailForm' => $validateMassMailForm->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $from
     * @param $to
     * @return bool
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/workers/firms/{from}/{to}/experiences/update", name="updateNbExpsInAllFirms")
     */
    public function updateNbExpsInAllFirms(Request $request,$from,$to){

        $em = $this->em;
        $repoWF = $em->getRepository(WorkerFirm::class);
        $repoWE = $em->getRepository(WorkerExperience::class);
        $firmsIdsToSearch = [];
        for($i = $from; $i < $to; $i++){
            $firmsIdsToSearch[] = $i;
        }

        $wFirms = $repoWF->findById($firmsIdsToSearch);

        foreach($wFirms as $key => $wFirm){
            $nbActiveExp = count($repoWE->findBy(['firm' => $wFirm, 'active' => 1]));
            $nbOldExp = count($repoWE->findBy(['firm' => $wFirm, 'active' => 0]));
            $wFirm->setNbActiveExperiences($nbActiveExp)
                ->setNbOldExperiences($nbOldExp);
            $em->persist($wFirm);
            if($key % 100 == 0){
                $em->flush();
            }
        }
        $em->flush();
        return true;
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $from
     * @param $to
     * @return bool
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/workers/firms/{from}/{to}/mails/create", name="createMostPossibleMails")
     */
    public function createMostPossibleMails(Request $request, $from, $to){

        $em = $this->em;
        $repoWF = $em->getRepository(WorkerFirm::class);
        $firmsIdsToSearch = [];
        for($i = $from; $i < $to; $i++){
            $firmsIdsToSearch[] = $i;
        }
        $l = 0;

        $wFirms = $repoWF->findById($firmsIdsToSearch);

        foreach($wFirms as $wFirm){
            $website = $wFirm->getWebsite();
            $commonName = $wFirm->getCommonName();
            if($website != null || $commonName != null){
                if($website != null){

                    if(count(explode('//',$website)) > 1){
                        $suf = explode('//',$website)[1];
                    } else {
                        $suf = $website;
                    }
                    $domain = explode('/',$suf)[0];
                    $suffix = $domain;
                    while(count(explode('.',$suffix)) > 2){
                        $suffix = explode('.',$suffix,2)[1];
                    }
                    $wFirm->setMailPrefix(1)->setMailSuffix($suffix);
                }
                if($commonName != null){
                    $wFirm->setCommonName($wFirm->getName());
                }
                $em->persist($wFirm);
                if($l % 100 == 0){
                    $em->flush();
                }
                $l++;
            }
        }
        $em->flush();
        return true;
    }

    // Function which sets data into our DB

    /**
     * @param Request $request
     * @param Application $app
     * @return bool
     * @Route("/insert/json", name="insertLKJSONData")
     */
    public function insertLKJSONData(Request $request){

        try {
        $em = $this->em;
        $repoWF = $em->getRepository(WorkerFirm::class);

        if(isset($_POST['individuals'])){

            $repoWI = $em->getRepository(WorkerIndividual::class);
            $repoWE = $em->getRepository(WorkerExperience::class);

            foreach($_POST['individuals'] as $key => $individual){

                $fullName = $individual['name'];
                $fullUrl = $individual['url'];
                $tmp = explode("/",$fullUrl);
                $url = end($tmp);

                $worker = $repoWI->findOneBy(['url' => $url, 'fullName' => $fullName]);
                if($worker == null){
                    $worker = new WorkerIndividual;
                    $worker->setCreated(0);
                }

                    $worker
                        ->setUrl($url)
                        ->setFullName($fullName)
                        ->setCountry('LU')
                        ->setNbConnections($individual['experiences']['nbConnections']);

                    if($worker->getCreated() == null){
                        $worker->setCreated(0);
                    }

                foreach($individual['experiences'] as $key => $expType){
                    if($key == "cExp"){
                        $this->insertIndividualExperience($em, $repoWF, $repoWE, $worker, $expType);
                    } else if($key == "pExps") {
                        foreach($expType as $experience){
                            $this->insertIndividualExperience($em, $repoWF, $repoWE, $worker, $experience);
                        }
                    }
                }

                $em->persist($worker);
                $em->flush();
            }

        } else if (isset($_POST['firms'])) {

            $repoWFS = $em->getRepository(WorkerFirmSector::class);

            foreach($_POST['firms'] as $key => $firm){

                $urlElements = explode("/",$firm['url']);
                $workerFirm = $repoWF->findOneByUrl(array_pop($urlElements));

                $firmDetails = $firm['details'];
                foreach($firmDetails as $key => $data){
                    switch($key){
                        case 'activitySector' :
                            $firmSector = $repoWFS->findOneByName($data);
                            if($firmSector == null){
                                $firmSector = new WorkerFirmSector;
                                $firmSector->setName($data);
                                $em->persist($firmSector);
                                $em->flush();
                            }
                            $workerFirm->setMainSector($firmSector);
                            break;
                        case 'nbSubscribers' :
                            $workerFirm->setNbLKFollowers($data);
                            break;
                        case 'nbLKEmployees' :
                            $workerFirm->setNbLKEmployees($data);
                            break;
                        case 'website' :
                            $workerFirm->setWebsite($data);
                            break;
                        case 'cDate' :
                            $workerFirm->setCreationDate(new \DateTime($data.'-01-01'));
                            break;
                        case 'fType' :
                            switch($data){
                                case "Non lucratif" :
                                    $workerFirm->setFirmType(-3);
                                    break;
                                case "Administration publique" :
                                    $workerFirm->setFirmType(-2);
                                    break;
                                case "Établissement éducatif":
                                    $workerFirm->setFirmType(-1);
                                    break;
                                case "Travailleur indépendant ou profession libérale":
                                    $workerFirm->setFirmType(0);
                                    break;
                                case "Entreprise individuelle" :
                                    $workerFirm->setFirmType(1);
                                    break;
                                case "Société civile/Société commerciale/Autres types de sociétés" :
                                    $workerFirm->setFirmType(2);
                                    break;
                                case "Société de personnes (associés)" :
                                    $workerFirm->setFirmType(3);
                                    break;
                                case "Société cotée en bourse" :
                                    $workerFirm->setFirmType(4);
                                    break;
                                default:
                                    break;
                            }
                            break;
                        case 'fSize' :
                            switch($data){
                                case "1-10 employés" :
                                    $workerFirm->setSize(0);
                                    break;
                                case "11-50 employés" :
                                    $workerFirm->setSize(1);
                                    break;
                                case "51-200 employés" :
                                    $workerFirm->setSize(2);
                                    break;
                                case "201-500 employés" :
                                    $workerFirm->setSize(3);
                                    break;
                                case "501-1 000 employés" :
                                    $workerFirm->setSize(4);
                                    break;
                                case "1001-5 000 employés" :
                                    $workerFirm->setSize(5);
                                    break;
                                case "5 001-10 000 employés" :
                                    $workerFirm->setSize(6);
                                    break;
                                case "+ de 10 000 employés" :
                                    $workerFirm->setSize(7);
                                    break;
                                default:
                                    break;
                            }
                            break;
                        case 'HQLocation' :
                            $location = ucwords(strtolower($data));
                            $workerFirm->setHQLocation($location);

                            $splitLocation = explode(", ",$location,2);
                            $workerFirm->setHQCity($splitLocation[0]);
                            if(count($splitLocation) > 1) {
                                $workerFirm->setHQState($splitLocation[1]);
                            }
                            break;
                        case 'fCompetencies' :
                            foreach($data as $key => $firmCompetency){
                                $workerFirmCompetency = new WorkerFirmCompetency;
                                $firmCompetencyName = ucwords(trim($firmCompetency));
                                $workerFirmCompetency->setFirm($workerFirm)
                                    ->setName($firmCompetency);
                                $em->persist($workerFirmCompetency);
                            }
                        break;
                    }
                }
                $em->persist($workerFirm);

            }
            $em->flush();
        }


        }catch (Exception $e){
            print_r($e->getLine().' '.$e->getMessage());
            die;
        }
        return true;
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $from
     * @param $to
     * @return JsonResponse
     * @Route("/workers/firms/{from}/{to}/json-encode", name="transformFirmsIntoJSONVector")
     */
    public function transformFirmsIntoJSONVector(Request $request, $from, $to){
        $em = $this->em;
        $repoWF = $em->getRepository(WorkerFirm::class);
        $firmsIdsToSearch = [];
        for($i = $from; $i < $to; $i++){
            $firmsIdsToSearch[] = $i;
        }
        $firmsData = [];
        $wFirms = new ArrayCollection($repoWF->findById($firmsIdsToSearch));
        $searchableLKFirms = $wFirms->matching(Criteria::create()->where(Criteria::expr()->neq("url", null))->andWhere(Criteria::expr()->neq("url", "")));
        foreach($searchableLKFirms as $searchableLKFirm){
            $firmData = [];
            $firmData['name'] = $searchableLKFirm->getName();
            $firmData['url'] = 'https://lu.linkedin.com/company/'.$searchableLKFirm->getUrl();
            $firmsData[] = $firmData;
        }

        return new JsonResponse(['firms' => json_encode($firmsData)], 200);
    }
}
