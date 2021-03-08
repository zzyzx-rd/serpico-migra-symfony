<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 29/12/2017
 * Time: 17:35
 */

namespace App\Validator;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PasswordControlValidator extends ConstraintValidator
{

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function validate($value, Constraint $constraint)
    {
        $user = $constraint->user;
        $isPasswordValid = $this->encoder->isPasswordValid($user, $value);
        if(!$isPasswordValid) {
            $this->context->buildViolation($constraint->message)->addViolation();
            return false;
        }
        return true;
    }
}