<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 29/12/2017
 * Time: 17:35
 */

namespace App\Validator;


use Symfony\Component\Validator\Constraint;

class EDGreaterThanSD extends Constraint
{
    /**
     * @var string
     */
    public $message = "Enddate cannot be prior to Startdate";

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