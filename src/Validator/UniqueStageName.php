<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 26/12/2017
 * Time: 22:38
 */
namespace Validator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueStageName extends Constraint
{
    /**
     * @var string
     */
    public $message = "activity_elements.stages.unique_stage_name";
    public $element;
    public function __construct($options){
        parent::__construct($options);
    }

    public function getElement(){
        return $this->element;
    }

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