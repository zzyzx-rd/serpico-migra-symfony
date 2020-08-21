<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 29/12/2017
 * Time: 17:38
 */

namespace App\Validator;


use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

class GSDGreaterThanSDValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint){
        if($value < $this->context->getRoot()->get('startdate')->getData()){
            $this->context->buildViolation($constraint->message)->addViolation();
            return false;
        }
        return true;
    }
}