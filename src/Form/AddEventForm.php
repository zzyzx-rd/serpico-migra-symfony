<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etudiant
 * Date: 07/12/2017
 * Time: 09:35
 */
namespace App\Form;

use App\Entity\Event;
use App\Entity\EventType;
use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\Type\EventDocumentType;
use App\Form\Type\EventCommentType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class AddEventForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $currentUser = $options['currentUser'];

        $builder->add('eventType', EntityType::class,
        [
            'label_format' => 'activity_elements.events.add_event.%name%',
            'class' => EventType::class,
            'choice_label' => static function(EventType $et) {
                $iconObj = $et->getIcon();
                $icon = ($iconObj and $iconObj->getType() !== 'm') ? '~'.$iconObj->getUnicode().'~ ' : '~f1b2~ ';
                return $icon . $et->getEName()->getName();
            },               
            'choice_attr' => static function(EventType $et) {
                $iconObj = $et->getIcon();
                return $iconObj ? 
                    [
                        'class' => ($iconObj->getType() !== 'm') ? $et->getIcon()->getType() . ' fa-'.$et->getIcon()->getName() : $et->getIcon()->getName(),
                        //'data-icon' => ($iconObj->getType() !== 'm') ? $iconObj : '',
                        //'data-egn' => $et->getEventGroup()->getEventGroupName()->getId(),
                        //'data-en' => $et->getEName()->getId(),
                    ]
                    : [];
            },       
            'group_by' => 'eventGroup',
            'query_builder' => static function(EntityRepository $er) use ($currentUser) {
                $orgId = $currentUser->getOrganization()->getId();
                return $er->createQueryBuilder('et')
                    ->where("et.organization = $orgId")
                    ->orderBy('et.type', 'asc')
                    ->addOrderBy('et.id', 'asc');
            },
            'attr' => [
                'class' => 'select-with-fa'
            ]
        ])
        ->add('comments', CollectionType::class,
        [
            'label' => false,
            'entry_type' => EventCommentType::class,
            'prototype' => true,
            'by_reference' => false,
            'allow_delete' => true,
            'allow_add' => true,
        ])
        ->add('documents', CollectionType::class,
        [
            'label' => false,
            'entry_type' => EventDocumentType::class,
            'entry_options' => [
                'currentUser' => $options['currentUser']
            ],
            'prototype' => true,
            'by_reference' => false,
            'allow_delete' => true,
            'allow_add' => true,
        ])

        ->add('onsetDate', DateTimeType::class,
        [
            'format' => 'd MMMM, y',
            'widget' => 'single_text',
            'label_format' => 'activity_elements.events.dates.onset_date',
            'html5' => false,
            'attr' => ['class' => 'dp-start no-margin'],

        ])
        ->add('expResDate', DateTimeType::class,
        [
            'format' => 'd MMMM, y',
            'widget' => 'single_text',
            'label_format' => 'activity_elements.events.dates.exp_res_date',
            'html5' => false,
            'attr' => ['class' => 'dp-end no-margin'],
        ]);


        if ($options['standalone']){
            $builder->add('submit', SubmitType::class,[
                'label' => '[link]',
                'attr' =>
                    [
                        'class' => 'waves-effect waves-light btn btn-e-update update-event-btn',
                    ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', Event::class);
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');
        $resolver->setDefault('currentUser', null);
    }

}
