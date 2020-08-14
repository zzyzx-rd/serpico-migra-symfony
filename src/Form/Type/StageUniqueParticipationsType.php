<?php
namespace App\Form\Type;

use App\Entity\IProcessStage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Stage;
use App\Entity\TemplateStage;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;


class StageUniqueParticipationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if($options['mode'] == 'manage'){
            $builder->add('uniqueParticipations', CollectionType::class,
                [
                    'label' => false,
                    'entry_type' => ParticipantManageType::class,
                    'entry_options' => [
                        'organization' => $options['organization'],
                        'elmt' => $options['elmt']
                    ],
                    'prototype' => true,
                    'by_reference' => false,
                    'allow_delete' => true,
                    'allow_add' => true,
                ]
            );
        } else {
            $builder->add('userGradableParticipations', CollectionType::class,
                [
                    'label' => false,
                    'entry_type' => ParticipantGradeType::class,
                    'prototype' => false,
                    'by_reference' => false,
                    'allow_delete' => 'false',
                    'allow_add' => 'false',
                ])

                ->add('teamGradableParticipations', CollectionType::class,
                [
                    'label' => false,
                    'entry_type' => ParticipantGradeType::class,
                    'by_reference' => false,
                ]);


                if($builder->getData()->getMode() != 1){

                    $builder->add('selfGrades', CollectionType::class,
                    [
                        'label' => false,
                        'entry_type' => GradeType::class,
                        'by_reference' => false,

                    ]);
                }

            if ($options['standalone']) {
                $builder
                ->add('save', SubmitType::class,
                    [
                        'label_format' => 'grades.%name%',
                        'attr' => [
                            'class' => 'btn-large waves-effect back-button waves-light teal lighten-1',
                        ]
                    ]
                )
                ->add('finalize', SubmitType::class,
                    [
                        'label_format' => 'grades.%name%',
                        'attr' => [
                            'class' => 'btn-large next-button waves-effect waves-light blue darken-4',
                        ]
                    ]
                );
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('elmt', 'activity');
        $resolver->setDefault('data_class',function (Options $options){
            if($options['elmt'] == 'iprocess'){
                return IProcessStage::class;
            } else if($options['elmt'] == 'template'){
                return TemplateStage::class;
            }
            return Stage::class;
        });
        $resolver->setDefault('organization', null);
        $resolver->setDefault('standalone', false);
        $resolver->setDefault('mode', 'manage');
        $resolver->addAllowedTypes('standalone', 'bool');
    }
}
