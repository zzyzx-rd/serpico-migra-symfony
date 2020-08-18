<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 26/12/2017
 * Time: 22:38
 */
namespace Validator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class EmailUniqueness extends Constraint
{
    /**
     * @var string
     */
    public $message = "create_user.mail";

    public function validatedBy()
    {
        return 'email.uniqueness.validator';
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