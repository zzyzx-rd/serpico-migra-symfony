<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 29/12/2017
 * Time: 17:38
 */

namespace App\Validator;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Activity;
use App\Entity\CriterionName;
use App\Entity\Department;
use App\Entity\InstitutionProcess;
use App\Entity\Organization;
use App\Entity\Client;
use App\Entity\Position;
use App\Entity\Title;
use App\Entity\User;
use App\Entity\Weight;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;


class UniquePerOrganizationValidator extends ConstraintValidator
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function validate($value, Constraint $constraint){
        if($value != null){
            $em = $this->em;
            switch($constraint->entity){
                case 'activity' :
                    $possibleDuplicateElement = $em->getRepository(Activity::class)->findOneBy(['organization' => $constraint->organization, $constraint->property => $value]);
                    break;
                case 'criterionName' :
                    $possibleDuplicateElement = $em->getRepository(CriterionName::class)->findOneBy(['organization' => $constraint->organization, $constraint->property => $value]);
                    break;
                case 'institutionProcess' :
                    $possibleDuplicateElement = $em->getRepository(InstitutionProcess::class)->findOneBy(['organization' => $constraint->organization, $constraint->property => $value]);
                    break;
                case 'position' :
                    $possibleDuplicateElement = $em->getRepository(Position::class)->findOneBy(['organization' => $constraint->organization, $constraint->property => $value]);
                    break;
                case 'department' :
                    $possibleDuplicateElement = $em->getRepository(Department::class)->findOneBy(['organization' => $constraint->organization, $constraint->property => $value]);
                    break;
                case 'title' :
                    $possibleDuplicateElement = $em->getRepository(Title::class)->findOneBy(['organization' => $constraint->organization, $constraint->property => $value]);
                    break;
                case 'weight' :
                    $possibleDuplicateElement = $em->getRepository(Weight::class)->findOneBy(['organization' => $constraint->organization, $constraint->property => $value]);
                    break;
                case 'client' :
                    $possibleDuplicateElement = $em->getRepository(Client::class)->findOneBy(['organization' => $constraint->organization, $constraint->property => $value]);
                    break;
                case 'user' :
                    $possibleDuplicateElement = ($constraint->organization != null) ? 
                        $em->getRepository(User::class)->findOneBy(['organization' => $constraint->organization, $constraint->property => $value]) :
                        $em->getRepository(User::class)->findOneBy([$constraint->property => $value]);
                    break;
                default:
                    break;
            }
            if($possibleDuplicateElement != null && (
                $constraint->element != null && $possibleDuplicateElement->getId() != $constraint->element->getId() 
                || $constraint->element == null
                || $constraint->element->getId() == 0) 
            ){
                $this->context->buildViolation($constraint->message)->addViolation();
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }
}