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
class UBEmpGreaterThanLBEmp extends Constraint
{
    /**
     * @var string
     */
    public $message = "worker_search.ub_greater_than_lb";

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