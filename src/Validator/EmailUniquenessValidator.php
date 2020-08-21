<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 29/12/2017
 * Time: 17:38
 */

namespace App\Validator;


use Doctrine\ORM\EntityManagerInterface;
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
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function validate($value, Constraint $constraint): bool
    {

        if($this->em->getRepository(User::class)->findByEmail($value)->isEmpty()){
            $this->context->buildViolation($constraint->message)->addViolation();
            return false;
        }
        return true;
    }
}