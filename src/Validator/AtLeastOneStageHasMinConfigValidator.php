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

class AtLeastOneStageHasMinConfigValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint){

        /** @var ArrayCollection|Stage[]| */
        $activeModifiableStages = $this->context->getObject()->getParent()->get('activeModifiableStages')->getData();

        $haveAllStagesMinConfig = $activeModifiableStages->exists(function(int $i, Stage $s){
            return $s->hasMinimumOutputConfig() && $s->hasMinimumParticipationConfig();
        });

        if(!$haveAllStagesMinConfig){
            $this->context->buildViolation($constraint->message)->addViolation();
            return false;
        } 

        return true;
    }
       
}