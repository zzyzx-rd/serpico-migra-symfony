<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 26/12/2017
 * Time: 22:38
 */
namespace App\Validator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniquePerOrganization extends Constraint
{
    /**
     * @var string
     */
    
    public $message;
    public $specificMessage;
    public $organization;
    public $entity;
    public $element;
    public $property;

    public function __construct($options){
        parent::__construct($options);
    }

    public function getOrganization(){
        return $this->organization;
    }
    public function getElement(){
        return $this->element;
    }
    public function getEntity(){
        return $this->entity;
    }
    public function getProperty(){
        return $this->property;
    }

    public function getMessage(){
        return $this->message;
    }

    public function getEntityManager(){
        return $this->em;
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