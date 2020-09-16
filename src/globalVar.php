<?php
namespace App;
use App\Entity\Activity;
use App\Entity\Client;
use App\Entity\Organization;
use App\Entity\Stage;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class globalVar {

    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var Security
     */
    private $security;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack, Security $security)
    {
        $this->requestStack = $requestStack;
        $this->security = $security;
        $this->em = $entityManager;
    }

    public function route(){
        return $this->request()->get('_route');
    }

    public function routeParams(): array
    {
        return ["_locale" =>"fr"];
    }

    public function userPicture(): string
    {
        $userPicture = $this->CurrentUser() ? $this->CurrentUser()->getPicture() : null;
        return 'lib/img/user/' . ($userPicture ?: 'no-picture.png');
    }


    public function organizationLogo(?Organization $o): string
    {
        if($o && $o->getLogo()){
            return 'lib/img/org/'. $o->getLogo();
        } else if ($o && $o->getWorkerFirm() && $o->getWorkerFirm()->getLogo()){
            return 'lib/img/org/' . $o->getWorkerFirm()->getLogo();
        } else {
            return 'lib/img/org/no-picture.png';
        }
    }
    
    public function clientLogo(Client $c): string
    {
        if($c->getLogo()){
            return 'lib/img/org/' . $c->getLogo();
        }

        return $this->organizationLogo($c->getClientOrganization());
    }

    public function teampicture(){
        $teamPicture = $this->CurrentUser()?$this->CurrentUser()->getPicture(): null;
        return 'lib/img/team/' . ($teamPicture ?: 'no-picture.png');
    }
    
    public function request(): ?\Symfony\Component\HttpFoundation\Request
    {
        return $this->requestStack->getCurrentRequest();
    }

    /**
     * @return User|UserInterface|null
     */
    public function CurrentUser()
    {
        return $this->security->getUser();
    }

    public function html_data_params(array $arr): string
    {
        $dataParams = [];
        foreach ($arr as $key => $value) {
            $dataParams[] = "data-$key=\"$value\"";
        }

        return implode(' ', $dataParams);
    }

    public function activeUsers(): ArrayCollection
    {
        $currentUser = $this->CurrentUser();
        $org = $currentUser?$currentUser->getOrganization():null;
        return new ArrayCollection($this->em->getRepository(User::class)
            ->findBy(['organization' => $org, 'deleted' => null],['lastname' => 'ASC']));
    }


}
