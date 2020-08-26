<?php
namespace App\Model;
use App\Entity\Activity;
use App\Entity\Client;
use App\Entity\Organization;
use App\Entity\Stage;
use App\Entity\Team;
use App\Entity\Member;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class OrganizationM extends ModelEntity {

    public function getDealingTeams(Organization $organization){
        
        $repoT = $this->em->getRepository(Team::class);
        $teams = new ArrayCollection($repoT->findAll());
        $currentOrg = $this->currentUser->getOrganization();
        return $teams->filter(function(Team $t) use ($currentOrg){ 
            return $t->getMembers()->exists(function(int $i, TeamUser $tu) use ($currentOrg){
                if(!$tu->getExternalUser()){
                    return false;
                }
                $orgUsers = new ArrayCollection($currentOrg->getOrgUsers());
                return $orgUsers->contains($tu->getExternalUser()->getUser());
            });
        });
    }

    public function getDealingFirms(){
        $em = $this->em;
        $repoO = $em->getRepository(Organization::class);
        $organizations = new ArrayCollection($repoO->findAll());
        $currentOrg = $this->currentUser->getOrganization();
        return $organizations->filter(function(Organization $o) use ($currentOrg){ 
            return $o->getClients()->exists(function(int $i, Client $c) use ($currentOrg){
                return $c->getClientOrganization() == $currentOrg;
            });
        });
    }


}