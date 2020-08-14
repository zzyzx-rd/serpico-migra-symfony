<?php
namespace App;
use App\Entity\User;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

class globalVar {

    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var Security
     */
    private $security;

    public function __construct(RequestStack $requestStack, Security $security)
    {
        $this->requestStack = $requestStack;
        $this->security = $security;
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

    /**
     * @return User|null
     */
    public function CurrentUser(): ?User
    {
        return $this->security->getUser();
    }
}
