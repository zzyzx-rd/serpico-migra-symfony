<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 29/12/2017
 * Time: 17:35
 */

namespace Validator;


use Symfony\Component\Validator\Constraint;

class MTGreaterThanToday extends Constraint
{
    /**
     * @var string
     */
    public $message = "Meeting date should be greater than today";

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