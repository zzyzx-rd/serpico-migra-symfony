<?php


namespace App\Model;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class ModelEntity
{
    /**
     * @var RequestStack
     */
    protected $requestStack;
    /**
     * @var Security
     */
    protected $security;

    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var UserInterface|null
     */
    protected $currentUser;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack, Security $security)
    {
        $this->requestStack = $requestStack;
        $this->security = $security;
        $this->currentUser = $this->security->getUser();
        $this->em = $entityManager;
    }
}