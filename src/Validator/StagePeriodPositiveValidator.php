<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 27/12/2017
 * Time: 10:42
 */

namespace Validator;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

class StagePeriodPositiveValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if(!$this->context->getObject()->getParent()->get('definiteDates')){
            if($value < 1){
                $this->context->buildViolation($constraint->message)->addViolation();
                return false;
            }
        }
        return true;
    }
}