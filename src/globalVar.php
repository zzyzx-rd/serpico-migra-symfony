<?php
namespace App;
use App\Entity\Activity;
use App\Entity\Client;
use App\Entity\ElementUpdate;
use App\Entity\EventType;
use App\Entity\EventComment;
use App\Entity\Organization;
use App\Entity\Stage;
use App\Entity\User;
use App\Entity\WorkerFirm;
use DateTime;
use DateTimeZone;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Contracts\Translation\TranslatorInterface;

class globalVar {

    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var Security
     */
    private $security;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack, Security $security, TranslatorInterface $translator)
    {
        $this->requestStack = $requestStack;
        $this->security = $security;
        $this->em = $entityManager;
        $this->translator = $translator;
    }

    public function route(){
        return $this->request()->get('_route');
    }

    public function routeParams(): array
    {
        return ["_locale" =>"fr"];
    }

    public function userPicture(User $user = null): string
    {
        $userPicture = $user ? $user->getPicture() : ($this->CurrentUser() ? $this->CurrentUser()->getPicture() : null);
        return 'lib/img/user/' . ($userPicture ?: 'no-picture.png');
    }


    public function workerFirmLogo(?WorkerFirm $wf): string
    {
        if($wf->getLogo()){
            return 'lib/img/wf/' . $wf->getLogo();
        } else {
            return 'lib/img/org/no-picture.png';
        }
    }

    public function organizationLogo(?Organization $o): string
    {
        if($o && $o->getLogo()){
            return 'lib/img/org/'. $o->getLogo();
        } else if ($o && $o->getWorkerFirm() && $o->getWorkerFirm()->getLogo()){
            return 'lib/img/wf/' . $o->getWorkerFirm()->getLogo();
        } else {
            return $o->getType() == 'I' ? "lib/img/org/indpt-no-picture.png" : "lib/img/org/no-picture.png";
        }
    }
    
    public function clientLogo(?Client $c): string
    {
        if(!$c){
            return 'lib/img/org/no-picture.png';
        }

        if($c->getLogo()){
            return 'lib/img/org/' . $c->getLogo();
        }

        return $this->organizationLogo($c->getClientOrganization());
    }

    public function teampicture(){
        $teamPicture = $this->CurrentUser()?$this->CurrentUser()->getPicture(): null;
        return 'lib/img/team/' . ($teamPicture ?: 'no-picture.png');
    }
    
    public function request(): ?\Symfony\Component\HttpFoundation\Request
    {
        return $this->requestStack->getCurrentRequest();
    }

    /**
     * @return User|UserInterface|null
     */
    public function CurrentUser()
    {
        return $this->security->getUser();
    }

    public function html_data_params(array $arr): string
    {
        $dataParams = [];
        foreach ($arr as $key => $value) {
            if(is_string($value)){
                $dataParams[] = "data-$key=$value";
            } else {
                $dataParams[] = "data-$key=\"$value\"";
            }
        }
        return implode(' ', $dataParams);
    }

    public function activeUsers(): ArrayCollection
    {
        $currentUser = $this->CurrentUser();
        $org = $currentUser ? $currentUser->getOrganization():null;
        return new ArrayCollection($this->em->getRepository(User::class)
            ->findBy(['organization' => $org, 'deleted' => null],['lastname' => 'ASC']));
    }

    public function updates(){
        $maxRetrieved = 7;
        /** @var User */
        $currentUser = $this->CurrentUser();
        $org = $currentUser->getOrganization();
        $updates = $currentUser->getUpdates()->filter(fn(ElementUpdate $u) => $u->getUser() == $currentUser)->matching(Criteria::create()->orderBy(['viewed' => Criteria::DESC, 'stage' => Criteria::ASC]));
        $em = $this->em;
        $dataUpdates = [];
        $repoET = $em->getRepository(EventType::class);
        if($updates->count() > 0){
            
            $dataUpdates['nbNew'] = $updates->filter(fn(ElementUpdate $u) => $u->getViewed() == null)->count();
            $tz = new DateTimeZone('Europe/Paris');
            $locale = $this->request()->getLocale();
            $translator = $this->translator;
            $repoU = $this->em->getRepository(User::class);
            $nbRetrieved = 0;
            foreach ($updates as $update){
                if($nbRetrieved == $maxRetrieved){
                    break;
                }
                if($update->getActivity()){
                    // Different case : first, updates related to activities.
                    // Then can be on : act/stg creation, participation, event and related documents/comments, criterion, output
                    $comment = $update->getEventComment();
                    $document = $update->getEventDocument();
                    $event = $update->getEvent();
                    $participation = $update->getParticipation();
                    $stage = $update->getStage();
                    $activity = $update->getActivity();
                    $stageName = $stage->getName();
                    $activityName = $activity->getName();
                    $of = $translator->trans('of');
                    $theStage = $translator->trans('the_phase');
                    $theActivity = $translator->trans('the_activity');
                
                    if($event && ($document || $comment)){
                        $theEvent = $translator->trans('the_event');
                        $eventType = $event->getEventType();
                        $eventTypeName = strtolower($repoET->getDTrans($eventType, $locale, $org));
                    }

                    $transParameters = [];
                    $transParameters['actElmtMsg'] = 
                        ($event && ($document || $comment) ? "$theEvent $of <span class=\"strong\">$eventTypeName</span> $of " : "") .
                        
                        ($activity->getStages()->count() > 1 ? 
                        "$theStage <span class=\"strong\">$stageName</span> $of $theActivity <span class=\"strong\">$activityName</span>" :
                        "$theActivity <span class=\"strong\">$activityName</span>");

                    if($document != null || $comment != null){
                        $creator = $repoU->find($document ? $document->getCreatedBy() : $comment->getCreatedBy());
                        $transParameters['author'] = $creator->getOrganization() != $currentUser->getOrganization() ? $creator->getFullName(). ' ('.$creator->getOrganization()->getCommname().')' : $creator->getFullName();
                        //$dataUpdate['type'] = $document ? 'd' : 'c';
                        $transParameters['updateType'] = $comment ? 
                            ($comment->getParent() ? 
                                $translator->trans('updates.comment_update_type.withParent') : 
                                $translator->trans('updates.comment_update_type.withoutParent')
                            ) : 
                            ($update->getType() == ElementUpdate::CREATION ?
                                $translator->trans('updates.document_update_level.creation') : 
                                $translator->trans('updates.document_update_level.update')
                            );
                        
                        $transParameters['update_type'] = $document ? 'event_document' : 'event_comment';
                        if($document){
                            $transParameters['docName'] = $document->getTitle();
                        }
                        
                    } else if ($event != null && $document == null && $comment == null) {
                        $creator = $repoU->find($event->getCreatedBy());
                        $transParameters['update_type'] = 'event_creation';
                        $eventType = $event->getEventType();
                        $transParameters['type'] = strtolower(implode("_",explode(" ",$eventType->getEName()->getName())));
                        $transParameters['group'] = strtolower($eventType->getEventGroup()->getEventGroupName()->getName());
                    } else if ($participation != null) {
                        $creator = $repoU->find($participation->getCreatedBy());
                        $dataUpdate['type'] = 'p';
                    } else {
                        $creator = $repoU->find($stage->getCreatedBy());
                        $dataUpdate['type'] = 's';
                        $transParameters['update_type'] = 'act_elmt_creation';
                    }
                    
                    $dataUpdate['id'] = $update->getId();
                    $dataUpdate['picture'] = $creator->getPicture() ?: 'no-picture.png';
                    $dataUpdate['inserted'] = $this->nicetime($update->getInserted()->setTimezone($tz), $locale);
                    $transParameters['author'] = $creator->getOrganization() != $currentUser->getOrganization() ? $creator->getFullName(). ' ('.$creator->getOrganization()->getCommname().')' : $creator->getFullName();
                    $transParameters['updateLevel'] = $update->getType() == ElementUpdate::CREATION ? $translator->trans($document ? 'updates.document_update_level.creation' : 'updates.common_level.creation') : (ElementUpdate::CHANGE ? $translator->trans($document ? 'updates.document_update_level.update' : 'updates.common_level.update') : $translator->trans('updates.common_level.delete'));
                    $dataUpdate['msg'] = $translator->trans('updates.update_msg', $transParameters);
                    $dataUpdate['viewed'] = $update->getViewed() != null;
                    $dataUpdate['id'] = $update->getId();
                }

                $dataUpdates['notifs'][] = $dataUpdate;
                $nbRetrieved++;
            }

        } 

        return $dataUpdates;
    }

    function nicetime(DateTime $date, string $locale)
    {
        if(empty($date)) {
            return "No date provided";
        }
        
        switch($locale){
            case 'en' :
                $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
                $nowMsg = "Now";
                break;
            case 'fr' :
                $periods = array("seconde", "minute", "heure", "jour", "semaine", "mois", "année", "décennie");
                $nowMsg = "A l'instant";
                break;
            case 'es' :
                $periods = array("secundo", "minuto", "hora", "dia", "semana", "mes", "año", "decena");
                $nowMsg = "Ahora";
                break;
        }

        $lengths         = array("60","60","24","7","4.35","12","10");
        $now             = time();
        $unix_date       = $date->getTimestamp();
    
        // check validity of date
        if(empty($unix_date)) {   
            return "Bad date";
        }

        // is it future date or past date
        if($now > $unix_date) {   
            $difference = $now - $unix_date;
            switch($locale){
                case 'fr' :
                    $tense = "il y a ";
                    break;
                case 'en' :
                    $tense = "ago";
                    break;
                case 'es' :
                    $tense = "hace";
                    break;
            }
        
        } else {
            $difference = $unix_date - $now;
            switch($locale){
                case 'fr' :
                    $tense = "dans";
                    break;
                case 'en' :
                    $tense = "from now";
                    break;
                case 'es' :
                    $tense = "en";
                    break;
            }
        }
    
        for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
            $difference /= $lengths[$j];
        }
    
        $difference = round($difference);
    
        if($difference != 1) {
            $periods[$j].= "s";
        }

        switch($locale){
            case 'en' :
                return $j == 0 ? $nowMsg : "$difference $periods[$j] {$tense}";
            case 'fr' :
            case 'es' :
                return $j == 0 ? $nowMsg : "{$tense} $difference $periods[$j]";
        }
        
    }


}
