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

class ForcedCommentValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint){

        $grade = $this->context->getObject()->getParent()->get('value')->getData();
        $threshold = $constraint->criterion->getForceCommentValue();

        if($constraint->criterion->isForceCommentCompare()){
            
            if($constraint->criterion->getType() != 1){ 
                if($value == null){
                    $this->context->buildViolation($constraint->message)->addViolation();
                    return false;
                }
            } else {
                switch($constraint->criterion->getForceCommentSign()){
                    case 'smaller' :
                        if($value == null && $grade < $threshold){
                            $this->context->buildViolation($constraint->message)->addViolation();
                            return false;
                        }
                        break;
                    case 'smallerEqual' :
                        if($value == null && $grade <= $threshold){
                            $this->context->buildViolation($constraint->message)->addViolation();
                            return false;
                        }
                        break;
                    default:
                        return true;
                    break;
                }
            }
        } else {
            return true;
        }
    }       
}