<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 26/12/2017
 * Time: 22:41
 */

namespace App\Validator;


use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

class ExtUserOwnerHasValidEmailAddressValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint){
        if($value && $this->context->getObject()->getParent()->get('email')->getData() == null){
            $this->context->buildViolation($constraint->message)->addViolation();
            return false;
        }
        return true;
    }
       
}