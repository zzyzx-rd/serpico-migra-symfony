<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SumWeightEqualToHundredPctValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $collectionForm = $this->context->getObject();
        $constraintMsg = ($collectionForm->getName() == 'criteria') ? $constraint->crtMessage : $constraint->stgMessage;
        $sumElementsWeight = 0;

        if (count($collectionForm->getData()) != 0) {
            foreach ($collectionForm->getData() as $elementData) {
                $sumElementsWeight += ($collectionForm->getName() == 'criteria') ? 100 * $elementData->getWeight() : 100 * $elementData->getActiveWeight();
            }

            if ($sumElementsWeight != 100) {
                $this->context->buildViolation($constraintMsg)->addViolation();
                return false;
            }
        }

        return true;
    }
}
