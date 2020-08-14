<?php
namespace App\Form;

use App\Entity\Activity;
use App\Entity\TemplateActivity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\Type\StageUniqueParticipationsType;

class ManageStageParticipantsForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $activity = $builder->getData();
        if ($options['elmt'] == 'template') {
            $toBeFinalized = false;
        } else {
            $toBeFinalized = !$activity->getIsFinalized();
        }

        $builder->add('activeModifiableStages', CollectionType::class,
            [
                'label' => false,
                'entry_type' => StageUniqueParticipationsType::class,
                'entry_options' => [
                    'label' => false,
                    'organization' => $options['organization'],
                    'elmt' => $options['elmt']
                ],
                'by_reference' => false
            ]
        );

        if ($options['standalone']) {
            $builder
            ->add('previous', SubmitType::class, [
                'label_format' => 'participants.%name%',
                'attr' => [
                    'class' => 'btn-large waves-effect waves-light blue darken-4 prev-button'
                ]
            ])
            ->add('back', SubmitType::class, [
                'label_format' => 'participants.%name%',
                'attr' => [
                    'class' => 'btn-large waves-effect waves-light teal lighten-1 back-button'
                ]
            ])
            ->add('finalize', SubmitType::class, [
                'label' => ($options['elmt'] == 'template') ? 'participants.save' : ($toBeFinalized ? 'participants.finalize' : 'participants.update'),
                'attr' => [
                    'class' => 'btn-large waves-effect waves-light blue darken-4 next-button'
                ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('elmt', 'activity');
        $resolver->setDefault('data_class', function (Options $options) {
            return $options['elmt'] == 'template'
                   ? TemplateActivity::class
                   : Activity::class;
        });
        $resolver->setDefault('standalone', false);
        $resolver->setDefault('organization', null);
        $resolver->addAllowedTypes('standalone', 'bool');
    }
}
