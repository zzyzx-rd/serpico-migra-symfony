<?php
namespace App;
use Symfony\Component\HttpFoundation\RequestStack;

class globalVar {

    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function route(){
        return "login";
    }
    public function routeParams(){
        return ["_locale" =>"fr"];
    }

    public function request(){
        return $this->requestStack->getCurrentRequest();
    }


}
