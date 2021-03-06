<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 29/12/2017
 * Time: 17:35
 */

namespace App\Validator;


use Symfony\Component\Validator\Constraint;

class GSDGreaterThanSD extends Constraint
{
    /**
     * @var string
     */
    public $message = "Grading startdate cannot be prior to activity startdate";

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