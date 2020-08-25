<?php

namespace App\Repository;

use App\Entity\CriterionName;
use App\Entity\Organization;
use App\Entity\Team;
use App\Entity\TeamUser;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Organization|null find($id, $lockMode = null, $lockVersion = null)
 * @method Organization|null findOneBy(array $criteria, array $orderBy = null)
 * @method Organization[]    findAll()
 * @method Organization[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganizationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Organization::class);
    }

    public function hasActiveAdmin(?Organization $organization){
        return $organization?$this->getActiveUsers($organization)
            ->exists(static function(int $i, User $u){
                return $u->getRole() == USER::ROLE_ADMIN && $u->getLastConnected() !== null;
            }): false;
    }

    public function getActiveUsers(Organization $organization): ArrayCollection
    {
        return new ArrayCollection($this->_em->getRepository(User::class)->findBy(['organization' => $organization, 'deleted' => null],['lastname' => 'ASC']));
    }

    public function getUsers(Organization $organization)
    {
        global $app;
        return new ArrayCollection($this->_em->getRepository(User::class)->findBy(['organization' => $organization],['lastname' => 'ASC']));
    }

    /**
     * @return ArrayCollection|Department[]
     */
    public function getUserSortedDepartments(User $user)
    {
        $orgDepartments = $user->getOrganization()->getDepartments();
        $userSortedDepartments = new ArrayCollection;
        $userDpt = $user->getDepartment();
        if($orgDepartments->contains($userDpt)){
            $userSortedDepartments->add($userDpt);
        }
        foreach($orgDepartments as $orgDepartment){
            if($orgDepartment != $userDpt){
                $userSortedDepartments->add($orgDepartment);
            }
        }
        return $userSortedDepartments;
    }

    public function findUsedCriterionNames(Organization $organization): array
    {
        $em = $this->getEntityManager();
        /** @var CriterionNameRepository */
        $cnRepo = $em->getRepository(CriterionName::class);

        $criterionNames = $cnRepo->findBy([ 'organization' => $organization ]);

        $usedCriteria = [];
        foreach ($criterionNames as $cn) {
            $count = $cnRepo->findCriterionNameUsage($cn);
            if ($count) {
                $cnId = $cn->getId();
                $usedCriteria[$cnId] = $count;
            }
        }

        return $usedCriteria;
    }
    // /**
    //  * @return Organization[] Returns an array of Organization objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Organization
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getDealingTeams(Organization $organization){
        
        $repoT = $this->_em->getRepository(Team::class);
        $teams = new ArrayCollection($repoT->findAll());
        return $teams->filter(function(Team $t) use ($organization){ 
            return $t->getTeamUsers()->exists(function(int $i, TeamUser $tu) use ($organization){
                if(!$tu->getExternalUser()){
                    return false;
                }
                return $organization->getUsers()->contains($tu->getExternalUser()->getUser());
            });
        });
    }
}
