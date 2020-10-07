<?php

namespace App\Controller;

use App\Entity\Mail;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class MailController extends MasterController {


    // Function which sends mail to user
    // TestorNot parameter defines if the mail is sent is a testing mode (0) or live (1)
    /**
     * @param User[] $recipients
     */
    public function sendMail(MailerInterface $mailer, Request $request, array $recipients, $actionType, $settings)
    {

        //try{

        

        $data = $settings;
        $em = $this->em;
        $twig = $this->twig;
        $data['actionType'] = $actionType;
        $data['currentuser'] = $this->user;
        $recipientUsers = true;

        if (isset($data['recipientUsers'])) {
            $recipientUsers = false;
        }

        foreach ($recipients as $key => $recipient) {
            
            $email = new TemplatedEmail();
            
            if($recipientUsers){

                $mail = new Mail;
                $mail->setType($actionType);
                $token = md5(rand());
                $mail
                    ->setUser($recipient)
                    ->setOrganization($recipient->getOrganization());
    
                if (isset($data['activity'])) {
                    $mail->setActivity($data['activity']);
                    if ($actionType == 'unvalidatedGradesStageJoiner') {
                        $mail->setStage($data['activity']->getStages()->first());
                    }
                }
    
                if (isset($data['stage'])) {
                    $mail->setStage($data['stage'])->setActivity($data['stage']->getActivity());
                }
    
                if (isset($data['pType'])) {
                    switch ($data['pType']) {
                        case 1:
                            $mail->setPersona('C');
                            break;
                        case 2:
                            $mail->setPersona('P');
                            break;
                        case 3:
                            $mail->setPersona('M');
                            break;
                        case 4:
                            $mail->setPersona('H');
                            break;
                        case 5:
                            $mail->setPersona('U');
                            break;
                        default:
                            break;
                    }
                }
                if (isset($data['language'])) {
                    switch ($data['language']) {
                        case 1:
                            $mail->setLanguage('Fr');
                            break;
                        case 2:
                            $mail->setLanguage('En');
                            break;
                        default:
                            $mail->setLanguage($data['language']);
                            break;
                    }
                } else {
                    $mail->setLanguage($request->getLocale());
                }
                if (isset($data['location'])) {
                    switch ($data['location']) {
                        case "FR":
                            $data['phone'] = '+33 6 60 62 94 08';
                            break;
                        default:
                            $data['phone'] = '+352 621 207 642';
                            break;
                    }
                }
    
                $organization = $recipient->getOrganization();
    
                $mail->setToken($token);
                $data['trkToken'] = $token;
    
                $em->persist($mail);
                $em->flush();

            }
            
            $email->embedFromPath('lib/img/logo_dd_p_l.png','logo_img');

            $data['logo_width_px'] = 80;
            $data['company_name'] = 'Dealdrive';
            $data['address'] = '38, route d\'Esch';
            $data['zipcode_city'] = 'L-1470 Luxembourg';
            $data['phone'] = '+352 28 79 97 18';
            $data['company_website'] = 'https://www.serpicoapp.com';
            $data['recipient'] = $recipient;
            $data['mailId'] = isset($mail) ? $mail->getId() : 0;
            $data['incubator_logo'] = null;

            if ($actionType == 'firstMailReminderTPeriod' || $actionType == 'prospecting_1') {
                $email->embedFromPath('lib/img/people-working-logo.png', 'people_working_logo');
            }

            if ($actionType == 'activityParticipation') {

            } else if ($actionType == 'registration' /*&& !$data['firstCreatedOrgUser']*/ || $actionType == 'externalInvitation') {
                $data['token'] = $settings['tokens'][$key];
            } else if ($actionType == 'updateProgressStatus'){
                $data['stage'] = $settings['stages'][$key];
            }

            $email->htmlTemplate('mails/'. $actionType . '.html.twig')
            ->context($data);

            $mailTemplate = $twig->load('mails/' . $actionType . '.html.twig');
            $mailingEmailAddress = $recipientUsers ? $recipient->getEmail() : $recipient;

            $email->from(new Address('no-reply@dealdrive.lu','Dealdrive'))
                ->to($mailingEmailAddress)
                ->subject($mailTemplate->renderBlock('subject', $data));
                
            if (isset($data['addPresFR']) and $data['addPresFR'] == 1) {
                $email->attachFromPath('lib/Data/Serpico_Presentation_FR.pdf','Presentation');
            }
            if (isset($data['addPresEN']) and $data['addPresEN'] == 1) {
                $email->attachFromPath('lib/Data/Serpico_Presentation_EN.pdf','Presentation');
            }

            $mailer->send($email);
        }

        return true;

        /*
        } catch(\Exception $e) {
            print_r($e->getFile() .'on line '. $e->getLine() .' : '. $e->getMessage());
            die;
        }
        */
    }


}