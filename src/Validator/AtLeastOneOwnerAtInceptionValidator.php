<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 26/12/2017
 * Time: 22:41
 */

namespace App\Validator;

use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Participation;
use App\Entity\ExternalUser;
use App\Entity\Stage;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

class AtLeastOneOwnerAtInceptionValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint){

        $clientForm = $this->context->getObject()->getParent();
        /** @var Client */
        $client = $clientForm->getData();

        // When client connects for the first time, set administration to someone (self user or someone else, and then validation is always passed)
        if($client->getId()){
            $clientHasGivenOwnerhship = $client->getClientOrganization()->isClient() == true;
            if($clientHasGivenOwnerhship){
                return true;
            }
        }

        $submittedClientUsers = $clientForm->get('aliveExternalUsers')->getData();
        if($submittedClientUsers == null || $submittedClientUsers->count() == 0){
            return true;
        } else {
            $clientOwners = $submittedClientUsers->filter(function(ExternalUser $eu){
                return $eu->isOwner() && $eu->getEmail() != null;
            });
        }

        if($clientOwners->count() == 0){
            //print_r($this->context->getObject()->getName());
            //die;
            //$this->context->getObject()->addError(new FormError($constraint->message));
            $this->context->buildViolation($constraint->message)->addViolation();
            return false;
        } 

        return true;
    }
       
}