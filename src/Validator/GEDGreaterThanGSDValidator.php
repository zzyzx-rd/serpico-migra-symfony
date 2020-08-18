<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 29/12/2017
 * Time: 17:38
 */

namespace Validator;


use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

class GEDGreaterThanGSDValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint){
        if($value < $this->context->getRoot()->get('gstartdate')->getData()){
            $this->context->buildViolation($constraint->message)->addViolation();
            return false;
        }
        return true;
    }
}