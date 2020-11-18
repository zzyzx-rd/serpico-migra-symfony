<?php

namespace App\Service;

use App\Controller\MailController;
use App\Entity\ElementUpdate;
use App\Entity\Participation;
use App\Entity\User;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
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

    public function registerUpdates($element, $status, $property = null, $toBeFlushed = false)
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
                } elseif($elmtClass == 'EventComment') {
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
        if($toBeFlushed){
            $em->flush();
        }
        return true;

    }

    public function retrieveUpdatesToBeMailed()
    {
        $em = $this->em;
        $now = new DateTime();
        $yesterday = new DateTime('yesterday');
        $allUsers = new ArrayCollection($em->getRepository(User::class)->findAll());

        $mailableUsers = $allUsers->filter(fn(User $u) => !$u->isSynthetic() && $u->getLastConnected() ? $now->getTimestamp() - $u->getLastConnected()->getTimestamp() > 24 * 60 * 60 : true);
        $recipients = [];
        $updates = [];

        
        foreach($mailableUsers as $mailableUser){

            $dataUpdate = [];

            $unseenUpdates = $mailableUser->getUpdates()
                ->filter(fn(ElementUpdate $eu) => $eu->getViewed() == null && $eu->getMailed() == null)
                ->matching(Criteria::create()->orderBy(['type' => Criteria::ASC, 'activity' => Criteria::DESC, 'stage' => Criteria::ASC, 'event' => Criteria::ASC, 'eventDocument' => Criteria::ASC, 'eventComment' => Criteria::ASC]));

            $creationUpdates = $unseenUpdates->filter(fn(ElementUpdate $eu) => $eu->getType() == ElementUpdate::CREATION);
            if($creationUpdates->count() > 0){
                $dataCreationUpdate['stages'] = $creationUpdates->filter(fn(ElementUpdate $eu) => $eu->getStage() != null && $eu->getParticipation() == null && $eu->getEvent() == null)->map(fn(ElementUpdate $eu) => $eu->getStage())->getValues();
                $dataCreationUpdate['events'] = $creationUpdates->filter(fn(ElementUpdate $eu) => $eu->getEvent() != null && $eu->getEventDocument() == null && $eu->getEventComment() == null)->map(fn(ElementUpdate $eu) => $eu->getEvent())->getValues();
                $dataCreationUpdate['documents'] = $creationUpdates->filter(fn(ElementUpdate $eu) => $eu->getEventDocument() != null)->map(fn(ElementUpdate $eu) => $eu->getEventDocument())->getValues();
                $dataCreationUpdate['comments'] = $creationUpdates->filter(fn(ElementUpdate $eu) => $eu->getEventComment() != null)->map(fn(ElementUpdate $eu) => $eu->getEventComment())->getValues();
                $dataUpdate['creations'] = $dataCreationUpdate;
            }

            $modificationUpdates = $unseenUpdates->filter(fn(ElementUpdate $eu) => $eu->getType() == ElementUpdate::CHANGE);
            if($modificationUpdates->count() > 0){
                $dataModificationUpdate['stages'] = $modificationUpdates->filter(fn(ElementUpdate $eu) => $eu->getStage() != null && $eu->getParticipation() == null && $eu->getEvent() == null)->map(fn(ElementUpdate $eu) => ['stage' => $eu->getStage(), 'property' => $eu->getProperty()])->getValues();
                $dataModificationUpdate['events'] = $modificationUpdates->filter(fn(ElementUpdate $eu) => $eu->getEvent() != null && $eu->getEventDocument() == null && $eu->getEventComment() == null)->map(fn(ElementUpdate $eu) => ['event' => $eu->getEvent(), 'property' => $eu->getProperty()])->getValues();
                $dataModificationUpdate['documents'] = $modificationUpdates->filter(fn(ElementUpdate $eu) => $eu->getEventDocument() != null)->map(fn(ElementUpdate $eu) => ['document' => $eu->getEventDocument(), 'property' => $eu->getProperty()])->getValues();
                $dataModificationUpdate['comments'] = $modificationUpdates->filter(fn(ElementUpdate $eu) => $eu->getEventComment() != null)->map(fn(ElementUpdate $eu) => ['comment' => $eu->getEventComment(), 'property' => $eu->getProperty()])->getValues();
                $dataUpdate['modifications'] = $dataModificationUpdate;
            }

            $deletionUpdates = $unseenUpdates->filter(fn(ElementUpdate $eu) => $eu->getType() == ElementUpdate::DELETION);
            if($deletionUpdates->count() > 0){
                $dataDeletionUpdate['stages'] = $deletionUpdates->filter(fn(ElementUpdate $eu) => $eu->getStage() != null && $eu->getParticipation() == null && $eu->getEvent() == null)->map(fn(ElementUpdate $eu) => $eu->getStage())->getValues();
                $dataDeletionUpdate['events'] = $deletionUpdates->filter(fn(ElementUpdate $eu) => $eu->getEvent() != null && $eu->getEventDocument() == null && $eu->getEventComment() == null)->map(fn(ElementUpdate $eu) => $eu->getEvent())->getValues();
                $dataDeletionUpdate['documents'] = $deletionUpdates->filter(fn(ElementUpdate $eu) => $eu->getEventDocument() != null)->map(fn(ElementUpdate $eu) => $eu->getEventDocument())->getValues();
                $dataDeletionUpdate['comments'] = $deletionUpdates->filter(fn(ElementUpdate $eu) => $eu->getEventComment() != null)->map(fn(ElementUpdate $eu) => $eu->getEventComment())->getValues();
                $dataUpdate['deletions'] = $dataDeletionUpdate;
            }

            if(sizeof($dataUpdate) > 0){
                $updates[] = $dataUpdate;
                $recipients[] = $mailableUser;
            }

            foreach($unseenUpdates as $unseenUpdate){
                $unseenUpdate->setMailed(new DateTime());
                $em->persist($unseenUpdate);
            }
        }
        
        return ['recipients' => $recipients, 'updates' => $updates];
    }
}