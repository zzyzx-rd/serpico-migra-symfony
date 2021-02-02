<?php


namespace App\EventListener;

use App\Entity\GeneratedError;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use App\Entity\User;

class ExceptionListener {


    public function __construct(Environment $twig, MailerInterface $mailer, Security $security, EntityManagerInterface $em, ContainerInterface $container)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
        $this->security = $security;
        $this->em = $em;
        $this->container = $container;
    }

    public function onKernelException(ExceptionEvent $event) : void
    {
        $request = $event->getRequest();

        // We only store non 404 errors happening in prod environment, others are manager through SF error interface/profiler
        
        /*
        if($request->server->get('APP_ENV') !='dev' && $event->getThrowable() && $event->getThrowable()->getStatusCode() != 404){

            $mailer = $this->mailer;
            $container = $this->container;
            $twig = $this->twig;
            $em = $this->em;
            if (!$em->isOpen()) {
                $em = $container->get('doctrine')->resetManager();  
            }
            
            $user = $this->security->getUser();
            $usrId = $user ? $user->getId() : null;
            $throwable = $event->getThrowable();
            $headers = $request->headers;
            $attributes = $request->attributes;
            $data = [];
            $data['company_name'] = 'Dealdrive';
            $data['logo_width_px'] = 110;
            $data['address'] = '38, route d\'Esch';
            $data['zipcode_city'] = 'L-1470 Luxembourg';
            $data['phone'] = '+352 28 79 97 18';
            $data['requestURI'] = $request->getRequestUri();
            $data['method'] = $request->getMethod();
            $data['route'] = $attributes->get('_route');
            $data['user_agent'] = $headers->get('user_agent');
            $data['referer'] = $headers->get('referer') ? basename($headers->get('referer')) : null;
            $data['locale'] = $request->getLocale();
            $data['file'] = basename($throwable->getFile());
            $data['line'] = $throwable->getLine();
            $data['message'] = $throwable->getMessage();
            $data['user'] = $user;

            $error = $em->getRepository(GeneratedError::class)->findOneBy(['message' => $data['message'], 'file' => $data['file']]);
            if(!$error){

                    $error = new GeneratedError();
                    $error->setUsrId($usrId)
                    ->setMethod($data['method'])
                    ->setRequestURI($data['requestURI'])
                    ->setRoute($data['route'])
                    ->setAgent($data['user_agent'])
                    ->setReferer($data['referer'])
                    ->setLocale($data['locale'])
                    ->setFile($data['file'])
                    ->setLine($data['line'])
                    ->setMessage($data['message']);
                
                $em->persist($error);
                $em->flush();
                
                $email = new TemplatedEmail();
                $mailTemplate = $twig->load('mails/errorNotification.html.twig');
                $email->from('Dealdrive <no-reply@dealdrive.app>')
                ->to('support@dealdrive.lu')
                ->subject($mailTemplate->renderBlock('subject', $data))
                ->htmlTemplate('mails/errorNotification.html.twig')
                ->context($data)
                ->embedFromPath('lib/img/logo_dd_p_l.png','logo_img');
                $mailer->send($email);
            }
            
        }
        */

        return;

        /*
       $response = new RedirectResponse("/error/error/error");
        $response->send();
         return;
        if (
            $_ENV['APP_ENV'] != 'prod'
            || !$event->isMasterRequest()
            || !$event->getThrowable() instanceof NotFoundHttpException
        ) {
            return;
        }
        */


    }
}

