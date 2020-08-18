<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 26/12/2017
 * Time: 22:41
 */

namespace Validator;


use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

class UniqueStageNameValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint){

        $stage = $this->context->getObject()->getParent()->getData();

        foreach($constraint->element->getStages() as $actStage){
            if($actStage != $stage && $actStage->getName() == $value){
                $this->context->buildViolation($constraint->message)->addViolation();
                return false;
            }
        }
        return true;
    }
       
}