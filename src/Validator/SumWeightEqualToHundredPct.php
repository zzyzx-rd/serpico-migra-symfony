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
class SumWeightEqualToHundredPct extends Constraint
{

     /**
     * @var string
     */
    public $crtMessage = "activity_elements.criteria.sum_weight_equal_to_hundred_pct";
    /**
     * @var string
     */
    public $stgMessage = "activity_elements.stages.sum_weight_equal_to_hundred_pct";
    

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