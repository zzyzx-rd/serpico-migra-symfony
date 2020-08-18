<?php

namespace Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MTGreaterThanTodayValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {

        $today = new \DateTime;
        $insertedValue = $value;

        if ($insertedValue < $today) {
            $this->context->buildViolation($constraint->message)->addViolation();
            return false;
        }
        return true;
    }
}
