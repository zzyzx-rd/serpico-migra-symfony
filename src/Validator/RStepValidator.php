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

class RStepValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $RLB = (int) $this->context->getRoot()->get('recurringLowerbound')->getData();
        $RUB = (int) $this->context->getRoot()->get('recurringUpperbound')->getData();
        if (intval(($RUB - $RLB) / $value) != (($RUB - $RLB) / $value)) {
            $this->context->buildViolation($constraint->message)->addViolation();
            return false;
        }
        return true;
    }
}