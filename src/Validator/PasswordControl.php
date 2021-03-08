<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 29/12/2017
 * Time: 17:35
 */

namespace App\Validator;


use Symfony\Component\Validator\Constraint;

class PasswordControl extends Constraint
{
    /**
     * @var string
     */
    public $message = "password.incorrect_current_password";

    /** @var User */
    public $user;
    
    public function __construct($options){
        parent::__construct($options);    
    }
    
    public function getUser(){
        return $this->user;
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