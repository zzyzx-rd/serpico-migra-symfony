<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 27/12/2017
 * Time: 10:42
 */

namespace App\Validator;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

class StepValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $LB = $this->context->getObject()->getParent()->get('lowerbound')->getData();
        $UB = $this->context->getObject()->getParent()->get('upperbound')->getData();
        if ($value != null && intval(($UB - $LB) / $value) != (($UB - $LB) / $value)) {
            $this->context->buildViolation($constraint->message)->addViolation();
            return false;
        }
        return true;
    }
}