<?php

namespace App\Service;

use App\Entity\ElementUpdate;
use App\Entity\Participation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class NotificationManager
{

    private $em;

    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
        $this->user = $security->getUser();

    }

    public function registerUpdates($element, $status, $property = null)
    {
        $em = $this->em;
        $currentUser = $this->user;
        $elmtArrayClass =  explode('\\',get_class($element));
        $elmtClass = array_pop($elmtArrayClass);
        $department = $position = $institutionProcess = $activity = $stage = $event = $eventDocument = $eventComment = $output = $criterion = $result = null;

        switch($elmtClass){
            case 'EventDocument' :
            case 'EventComment' :
            case 'Event' :
                if($elmtClass == 'EventDocument'){
                    $eventDocument = $element;
                } else {
                    $eventComment = $element;
                }
                if($elmtClass != 'Event'){
                    $event = $element->getEvent();
                } else {
                    $event = $element;
                }
                $stage = $event->getStage();
                $activity = $stage->getActivity();
                break;

            case 'Activity' :
                $activity = $element;
                $stage = $activity->getStages()->first();
                break;

            case 'Stage' :
                $stage = $element;
                $activity = $stage->getActivity();
                break;

        }

        $users = $stage->getParticipants()->map(fn(Participation $p) => $p->getUser());

        foreach($users as $user){
            if($user != $currentUser){
                
                $update = new ElementUpdate();
                $update->setUser($user)
                    ->setDepartment($department)
                    ->setPosition($position)
                    ->setInstitutionProcess($institutionProcess)
                    ->setActivity($activity)
                    ->setStage($stage)
                    ->setEvent($event)
                    ->setEventDocument($eventDocument)
                    ->setEventComment($eventComment)
                    ->setOutput($output)
                    ->setCriterion($criterion)
                    ->setResult($result)
                    ->setType($status)
                    ->setProperty($property)
                    ->setCreatedBy($currentUser->getId());
                $element->addUpdate($update);
            }
        }

        $em->persist($element);
        $em->flush();
        return true;

    }

    public function sendNotifications()
    {
        return true;
    }
}