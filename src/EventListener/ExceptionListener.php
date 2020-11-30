<?php


namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
class ExceptionListener {


    public function __construct(Environment $twig )
    {
        $this->twig = $twig;

    }
    public function onKernelException(ExceptionEvent $event) : void
    {

        $response = new RedirectResponse("http://127.0.0.1:8111/error/error/error");
        $response->send();
        dd($event);
         return;
        if (
            $_ENV['APP_ENV'] != 'prod'
            || !$event->isMasterRequest()
            || !$event->getThrowable() instanceof NotFoundHttpException
        ) {
            return;
        }


    }
}
