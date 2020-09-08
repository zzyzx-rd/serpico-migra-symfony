<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 07/01/2018
 * Time: 13:23
 */

namespace App\Form\Type;

use App\Entity\IProcessStage;
use App\Entity\ProcessStage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use App\Entity\Stage;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\StagePeriodPositive;
use App\Validator\UBGreaterThanLB;
use App\Validator\SumWeightEqualToHundredPct;
use App\Validator\UniquePerOrganization;


class StageCriterionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('criteria', CollectionType::class,
            [
                'entry_type' => CriterionType::class,
                'entry_options' => [
                    'currentUser' => $options["currentUser"],
                    'organization' => $options['organization'],
                    'entity' => $options['entity'],
                ],
                'prototype' => true,
                'prototype_name' => '__crtIndex__',
                'by_reference' => false,
                'allow_delete' => 'true',
                'allow_add' => 'true',
                'constraints' => [
                    new Assert\NotNull,
                    new SumWeightEqualToHundredPct,
                ],
                'label' => false,
                'error_bubbling' => false,
            ]
        )->add('visibility', ChoiceType::class,
        [
            'label' => 'stages.stage.visibility.label_title',
            'constraints' => [
                new Assert\GreaterThan(0),
            ],
            'choices' => [
                'stages.stage.visibility.public' => 1,
                'stages.stage.visibility.unlisted' => 2,
                'stages.stage.visibility.private' => 3,
            ],
            /*
            'attr' => [
                'placeholder' => 'stages.stage.visibility.private',
            ],
            */
        ]);

        //if($options['entity'] == 'process' || $options['entity'] == 'iprocess' ){
            $builder->add('name', TextType::class,
            [
                'constraints' => [
                    new Assert\NotBlank,
                ],
                'label_format' => 'stages.stage.%name%',
            ])
                ->add('activeWeight', PercentType::class,
            [
                'label_format' => 'stages.stage.%name%',
                'label_attr' => [
                    'class' => 'active',
                ],
                'attr' => ['class' => 'weight-input']
            ]);

            if($options['entity'] !== "activity"){

                $builder->add('definiteDates', CheckboxType::class,
                [
                    'label' => 'process_stages.dates_definition.label_title',
                    'attr' => [
                        'class' => 'filled-in'
                    ]
                ])

                ->add('dPeriod', IntegerType::class,
                [
                    'label_format' => 'stages.stage.%name%',
                    'constraints' => [
                        new StagePeriodPositive,
                    ],
                    'attr' => [
                        'style' => 'width: 50px',
                    ],
                    'empty_data' => 1,
                ])
                ->add('dFrequency', ChoiceType::class,
                [
                    'label_format' => 'stages.stage.frequency.label_title',
                    'constraints' => [
                        new Assert\NotNull,
                    ],
                    'choices' => [
                        'stages.stage.frequency.minutes' => 'm',
                        'stages.stage.frequency.hours' => 'H',
                        'stages.stage.frequency.business_days' => 'BD',
                        'stages.stage.frequency.days' => 'D',
                        'stages.stage.frequency.weeks' => 'W',
                        'stages.stage.frequency.months' => 'M',
                        'stages.stage.frequency.years' => 'Y',
                    ],
                ])
                ->add('dOrigin', ChoiceType::class,
                [
                    'label_format' => 'stages.stage.frequency.label_title',
                    'constraints' => [
                        new Assert\NotNull,
                    ],
                    'choices' => [
                        'stages.stage.origin.creation' => 0,
                        'stages.stage.origin.validation' => 1,
                    ],
                ])
                ->add('fPeriod', IntegerType::class,
                [
                    'label_format' => 'stages.stage.%name%',
                    'constraints' => [
                        new StagePeriodPositive,
                    ],
                    'attr' => [
                        'style' => 'width: 50px',
                    ],
                    'empty_data' => 1,
                ])
                ->add('fFrequency', ChoiceType::class,
                [
                    'label_format' => 'stages.stage.frequency.label_title',
                    'constraints' => [
                        new Assert\NotNull,
                    ],
                    'choices' => [
                        'stages.stage.frequency.minutes' => 'm',
                        'stages.stage.frequency.hours' => 'H',
                        'stages.stage.frequency.business_days' => 'BD',
                        'stages.stage.frequency.days' => 'D',
                        'stages.stage.frequency.weeks' => 'W',
                        'stages.stage.frequency.months' => 'M',
                        'stages.stage.frequency.years' => 'Y',
                    ],
                ])
                ->add('fOrigin', ChoiceType::class,
                [
                    'label_format' => 'stages.stage.frequency.label_title',
                    'constraints' => [
                        new Assert\NotNull,
                    ],
                    'choices' => [
                        'stages.stage.origin.creation' => 0,
                        'stages.stage.origin.validation' => 1,
                        'stages.stage.origin.start' => 2,
                        'stages.stage.origin.end' => 3,
                    ],
                ]);
            }

            $builder->add('startdate', DateTimeType::class,
            [
                'format' => 'dd/MM/yyyy',
                'widget' => 'single_text',
                'label_format' => 'stages.stage.%name%',
                'html5' => false,
                'attr' => ['class' => 'dp-start'],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'A recurring activity must have a startdate'
                    ]),
                ]
            ])

            ->add('enddate', DateTimeType::class,
            [
                'format' => 'dd/MM/yyyy',
                'widget' => 'single_text',
                'label_format' => 'stages.stage.%name%',
                'html5' => false,
                'attr' => ['class' => 'dp-end'],
                //'data' => $options['enddate'],
                'constraints' => [
                    new Assert\NotBlank,
                    //new EDGreaterThanSD
                ]
            ])


            ->add('mode', ChoiceType::class,
                [
                    'label_format' => 'stages.stage.%name%',
                    'choices' => [
                        //'create_parameters.type_relval_option' => 0,
                        'stages.stage.graded_participant_mode' => 1,
                        'stages.stage.graded_stage_mode' => 0,
                        'stages.stage.graded_stage_and_participant_mode' => 2
                    ],
                    'expanded' => true,
                    'multiple' => false,
                    'attr' => [
                        'style' => 'display:flex;justify-content:space-between',
                    ],
                ]
            );

            if($options['entity'] !== 'process'){

                $builder->add('intParticipants', CollectionType::class,
                    [
                        'label' => false,
                        'entry_type' => ParticipantManageType::class,
                        'entry_options' => [
                            'organization' => $options['organization'],
                            'currentUser' => $options['currentUser'],
                            'entity' => $options['entity'],
                            'query' => 'internal',
                        ],


                        'prototype' => true,
                        'prototype_name' => '__iPartIndex__',
                        'by_reference' => false,
                        'allow_delete' => true,
                        'allow_add' => true,
                    ]
                )

                ->add('extParticipants', CollectionType::class,
                    [
                        'label' => false,
                        'entry_type' => ParticipantManageType::class,
                        'entry_options' => [
                            'organization' => $options['organization'],
                            'currentUser' => $options['currentUser'],
                            'entity' => $options['entity'],
                            'query' => 'external',
                        ],
                        'prototype' => true,
                        'prototype_name' => '__ePartIndex__',
                        'by_reference' => false,
                        'allow_delete' => true,
                        'allow_add' => true,
                    ]
                )

                ->add('independantTeamParticipants', CollectionType::class,
                    [
                        'label' => false,
                        'entry_type' => ParticipantManageType::class,
                        'entry_options' => [
                            'organization' => $options['organization'],
                            'currentUser' => $options['currentUser'],
                            'entity' => $options['entity'],
                            'query' => 'team',
                        ],
                        'prototype' => true,
                        'prototype_name' => '__tPartIndex__',
                        'by_reference' => false,
                        'allow_delete' => true,
                        'allow_add' => true,
                    ]
                );

            }


        //}
    }

    public function getName()
    {
        return 'stageCriterion';
    }
    public function getParent()
    {
        return FormType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
        ->setDefaults([
            'entity' => 'activity',
            'data_class' => function(Options $options) {
                if($options['entity'] === 'template'){
                    return TemplateStage::class;
                } else if ($options['entity'] === 'iprocess') {
                    return IProcessStage::class;
                } else if ($options['entity'] === 'process') {
                    return ProcessStage::class;
                } else {
                    return Stage::class;
                }
            },
            'organization' => null,
        ]);
        $resolver->setRequired("currentUser");
    }
}
