<?php
namespace App\Model;
use App\Entity\Activity;
use App\Entity\Client;
use App\Entity\Department;
use App\Entity\Organization;
use App\Entity\OrganizationUserOption;
use App\Entity\Stage;
use App\Entity\Team;
use App\Entity\TeamUser;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class DepartmentM extends ModelEntity {

    public function getViewableUsers(Department $department){

        global $app;
        $sortedViewableUsers = [];
        $em = $this->em;
        /** @var User */
        $user = $this->currentUser;
        $orgFullViewOption = $user->getOrganization()->getOptions()->filter(function(OrganizationUserOption $o){
            return $o->getOName()->getName() == 'enabledUserSeeAllUsers' && $o->isOptionTrue();
        });
        $departmentUsers = $department->getUsers();
        $viewableUsers = new ArrayCollection;

        if($user->getRole() == 1 || $user->getRole() == 4 || $department->getMasterUser() == $user || $orgFullViewOption){

            $viewableUsers = $departmentUsers;

        } else {
            if($user->getDepartment() == $department){

                // If user has a superior...
                if($user->getSuperior() != null){
                    // Two cases
                        // a) if user N+2 manager lies outside his department, then again he can access to all his department mates; otherwise, he only access people under the supervision of his boss
                    if($user->getSuperior() == null || $user->getSuperior()->getSuperior()->getDepartment() != $department){
                        $viewableUsers = $departmentUsers;
                    } else {
                        // b) otherwise he only sees subordinates of his direct manager (same hierarchy line)
                        $viewableUsers = $user->getSuperior()->getSubordinates();
                    }

                    // If superior lies within the department, and has not been added, we add him
                    if(!in_array($user->getSuperior(), $viewableUsers) && $user->getSuperior()->getDepartment() == $department){
                        $viewableUsers->add($user->getSuperior());
                    }

                } else {
                    $viewableUsers = $departmentUsers;
                }
            // If we lie in another department, user can see users he himself created
            } else {

                foreach($departmentUsers as $departmentUser){
                    if($departmentUser->getCreatedBy() == $user->getId()){
                        $viewableUsers->add($departmentUser);
                    }
                }

                // and if superior lies outside user's department, and has not been added
                if($user->getSuperior() != null && $user->getSuperior()->getDepartment() == $department && !$viewableUsers->contains($user->getSuperior())){
                    $viewableUsers->add($user->getSuperior());
                }

            }
        }

        /** Sorting to make self user first */
        $sortedViewableUsers = new ArrayCollection;

        if($viewableUsers->contains($user->getSuperior())){
            $sortedViewableUsers->add($user->getSuperior());
        }

        if($viewableUsers->contains($user)){
            $sortedViewableUsers->add($user);
        }

        foreach($viewableUsers as $viewableUser){
            if($viewableUser != $user && $viewableUser != $user->getSuperior()){
                $sortedViewableUsers->add($viewableUser);
            }
        }

        return $sortedViewableUsers;
    }
}