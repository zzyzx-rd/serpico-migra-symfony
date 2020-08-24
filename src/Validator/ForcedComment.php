<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 26/12/2017
 * Time: 22:38
 */
namespace App\Validator;

use App\Entity\Criterion;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MissingOptionsException;

/**
 * @Annotation
 */
class ForcedComment extends Constraint
{


    public $criterion;

    public function __construct($options){

        parent::__construct($options);
            
    }

    public function getCriterion(){
        return $this->criterion;
    }

    /**
     * @var string
     */
    public $message = "grades.force_comment"/*$this->getCriterion()->getForceCommentValue()*/;

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