<?php

namespace App\Form;

use App\Form\Type\StageCriterionType;
use App\Entity\Activity;
use App\Entity\InstitutionProcess;
use App\Entity\Process;
use App\Entity\TemplateActivity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Validator\AtLeastOneConfiguredStageHasAOwner;
use Validator\AtLeastOneStageHasMinConfig;
use Validator\SumWeightEqualToHundredPct;
use Validator\UniquePerOrganization;

class ActivityElementForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $organization = $options['organization'];
        $element      = $builder->getData();

        $builder->add('activeModifiableStages', CollectionType::class,
            [
                'label'         => false,
                'entry_type'    => StageCriterionType::class,
                'entry_options' => [
                    'label'        => false,
                    'app'          => $options['app'],
                    'organization' => $organization,
                    'elmtType'         => $options['elmtType'],
                ],
                'prototype'     => true,
                'prototype_name' => '__stgIndex__',
                'by_reference'  => false,
                'allow_delete'  => 'true',
                'allow_add'     => 'true',

                'constraints'   => [
                    new SumWeightEqualToHundredPct,
                    $options['elmtType'] == 'activity' ? new AtLeastOneStageHasMinConfig : '',
                    $options['elmtType'] == 'activity' ? new AtLeastOneConfiguredStageHasAOwner : '',
                ],
                'error_bubbling' => false,
            ])
            ->add('name', TextType::class,
                [
                    'constraints' => [
                        new Assert\NotBlank,
                        new UniquePerOrganization([
                            'organization' => $organization,
                            'entity'       => 'activity',
                            'element'      => $element,
                            'property'     => 'name',
                        ]),
                    ],
                    'label' => false,
                ]);

        if ($options['elmtType'] == 'iprocess') {
            $builder->add(
                'approvable',
                CheckboxType::class,
                [
                    'attr'         => ['class' => 'filled-in'],
                    'label_format' => 'process_stages.%name%',
                    'required'     => true,
                ]
            );
        }

        if($options['elmtType'] == 'activity' && !$element->getIsFinalized()){

            $builder->add(
                'save',
                SubmitType::class,
                [
                    'label_format' => 'activity_elements.%name%',
                    'attr'         => [
                        'class' => 'btn-large waves-effect waves-light activity-element-save',
                    ],
                ]
            );
        }

        $builder->add(
            'update',
            SubmitType::class,
            [
                'label_format' => $options['elmtType'] != 'activity' ? 'activity_elements.save' : (!$element->getIsFinalized() ? 'activity_elements.finalize' : 'activity_elements.%name%'),

                'attr'         => [
                    'class' => 'btn-large waves-effect waves-light blue darken-4 activity-element-update',
                ],
            ]
        );

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'elmtType' => 'iprocess',
                'data_class'   => function (Options $options) {
                    switch ($options['elmtType']) {
                        case 'iprocess':
                            return InstitutionProcess::class;
                            break;
                        case 'process':
                            return Process::class;
                            break;
                        case 'template':
                            return TemplateActivity::class;
                            break;
                        case 'activity':
                            return Activity::class;
                            break;
                    }
                },
                'app'          => null,
                'organization' => null,
            ]);
    }
}
