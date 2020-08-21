<?php
namespace App;
use App\Entity\Activity;
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

    public function userpicture(): string
    {
        $userPicture = $this->CurrentUser()?$this->CurrentUser()->getPicture(): null;
        return 'lib/img/' . ($userPicture ?: 'no-picture.png');
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
