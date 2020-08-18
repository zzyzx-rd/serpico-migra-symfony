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

    public function userpicture(){
        $userPicture = $this->CurrentUser()?$this->CurrentUser()->getPicture(): null;
        return 'lib/img/' . ($userPicture ?: 'no-picture.png');
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

    public function html_data_params(array $arr){
        $dataParams = [];
        foreach ($arr as $key => $value) {
            $dataParams[] = "data-$key=\"$value\"";
        }

        return implode(' ', $dataParams);
    }
}
