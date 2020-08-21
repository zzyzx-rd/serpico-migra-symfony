<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 29/12/2017
 * Time: 17:35
 */

namespace App\Validator;


use Symfony\Component\Validator\Constraint;

class GEDGreaterThanGSD extends Constraint
{
    /**
     * @var string
     */
    public $message = "Grading enddate cannot be prior grading startdate";

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