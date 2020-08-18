<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 29/12/2017
 * Time: 17:38
 */

namespace Validator;


use Model\User;
use Silex\Application;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Doctrine\ORM\EntityManager;

class EmailUniquenessValidator extends ConstraintValidator
{

    /**
     * @var EntityManager
     */

    protected $em;
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function validate($value, Constraint $constraint){

        if($this->em->getRepository(User::class)->findByEmail($value)->isEmpty()){
            $this->context->buildViolation($constraint->message)->addViolation();
            return false;
        }
        return true;
    }
}