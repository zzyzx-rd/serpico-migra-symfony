<?php

namespace App\Form;

use App\Form\Type\StageCriterionType;
use App\Entity\Activity;
use App\Entity\InstitutionProcess;
use App\Entity\Process;
use App\Entity\Stage;
use App\Form\Type\ParticipantMinType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\AtLeastOneConfiguredStageHasAOwner;
use App\Validator\AtLeastOneStageHasMinConfig;
use App\Validator\SumWeightEqualToHundredPct;
use App\Validator\UniquePerOrganization;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ActivityMinElementForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $organization = $options['organization'];

        $builder
            ->add('name', TextType::class,
                [
                    'constraints' => [
                        new Assert\NotBlank,
                        new UniquePerOrganization([
                            'organization' => $organization,
                            'entity'       => 'activity',
                            'element'      => $builder->getData(),
                            'property'     => 'name',
                        ]),
                    ],
                    'label_format' => 'stages.stage.%name%',
                ])

            ->add('startdate', DateTimeType::class,
                [
                    'format' => 'd MMMM, y',
                    'widget' => 'single_text',
                    'label_format' => 'stages.stage.%name%',
                    'html5' => false,
                    'attr' => ['class' => 'dp-start'],
                    'constraints' => [
                        new Assert\NotBlank,
                    ]
                ])

            ->add('enddate', DateTimeType::class,
                [
                    'format' => 'd MMMM, y',
                    'widget' => 'single_text',
                    'label_format' => 'stages.stage.%name%',
                    'html5' => false,
                    'attr' => ['class' => 'dp-end no-margin dp-stage-enddate'],
                    /*'constraints' => [
                        new Assert\NotBlank,
                        //new EDGreaterThanSD
                    ]*/
                ])
            
            
            ->add('participants', CollectionType::class,
                [
                    'label' => false,
                    'entry_type' => ParticipantMinType::class,
                    'entry_options' => [
                        'organization' => $options['organization'],
                        'currentUser' => $options['currentUser'],
                        'query' => 'internal',
                    ],
                    'prototype' => true,
                    //'prototype_name' => '__iPartIndex__',
                    'by_reference' => false,
                    'allow_delete' => true,
                    'allow_add' => true,
                ]
            )

            /*
            ->add('complexify', SubmitType::class,
                [
                    'label_format' => 'activities.create.%name%',
                    'attr' => [
                        'class' => 'btn-flat flex-center waves-effect waves-light setup-activity',
                    ],
                ]
            )
            */

            ->add('submit', SubmitType::class,
                [
                    'label_format' => 'activities.create.%name%',
                    'attr' => [
                        'class' => 'btn waves-effect waves-light btn-s-update create-stage',
                    ],
                ]
            );
        

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'entity' => 'iprocess',
                'data_class'   => Stage::class,
                'organization' => null,
            ]);
        $resolver->setRequired("currentUser");
    }
}
