<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 26/12/2017
 * Time: 22:38
 */
namespace App\Validator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class AtLeastOneConfiguredStageHasAOwner extends Constraint
{
    /**
     * @var string
     */
    public $message = "activity_elements.stages.missing_owners";

    /**
     * Get class constraints and properties
     *
     * @return array
     */
    public function getTargets()
    {
        return array(self::CLASS_CONSTRAINT, self::PROPERTY_CONSTRAINT);
    }
}