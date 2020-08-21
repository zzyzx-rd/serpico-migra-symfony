<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 26/12/2017
 * Time: 22:41
 */

namespace App\Validator;

use Model\ActivityUser;
use Model\Stage;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

class AtLeastOneConfiguredStageHasAOwnerValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint){

        /** @var Activity */
        $activity = $this->context->getObject()->getParent()->getData();

        // This validator is not triggered if there is no configured stages
        if(!$activity->getActiveConfiguredStages()->count()){
            return true;
        } else {

            $atLeastOneStageHasAOwner = $activity->getActiveConfiguredStages()->exists(function(int $i, Stage $s){
                $participants = $s->getUniqueIntParticipations();

                if($participants != null){
                    return $participants->exists(function(int $i, ActivityUser $p){
                        return $p->isLeader() == true;
                    });
                } else {
                    return false;
                }
            });

        }
        if(!$atLeastOneStageHasAOwner){
            $this->context->buildViolation($constraint->message)->addViolation();
            return false;
        }

        return true;
        /*foreach(this->context->getParent()->get('activeModifiableStages') as $stage){
            foreach($stage->get('uniqueIntParticipations') as $participant){
                if($participant->get('leader')){

                    return true;
                }
            }
        }
        if($value <= $this->context->getObject()->getParent()->get('lowerbound')->getData()){
            $this->context->buildViolation($constraint->message)->addViolation();
            return false;
        }
        return true;*/
    }

}
