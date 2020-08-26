<?php

namespace App\Form;

use App\Form\Type\StageCriterionType;
use App\Entity\Activity;
use App\Entity\InstitutionProcess;
use App\Entity\Process;
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
                    'organization' => $organization,
                    "currentUser" => $options["currentUser"],
                    'entity'         => $options['entity'],
                ],
                'prototype'     => true,
                'prototype_name' => '__stgIndex__',
                'by_reference'  => false,
                'allow_delete'  => 'true',
                'allow_add'     => 'true',

                'constraints'   => [
                    new SumWeightEqualToHundredPct,
                    $options['entity'] === 'activity' ? new AtLeastOneStageHasMinConfig : '',
                    $options['entity'] === 'activity' ? new AtLeastOneConfiguredStageHasAOwner : '',
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

        if ($options['entity'] === 'iprocess') {
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

        if($options['entity'] === 'activity' && !$element->isFinalized()){

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

        if (!$element->isFinalized()) {
            $builder->add(
                'update',
                SubmitType::class,
                [
                    'label_format' => $options['entity'] !== 'activity' ? 'activity_elements.save' : ('activity_elements.finalize'),

                    'attr' => [
                        'class' => 'btn-large waves-effect waves-light blue darken-4 activity-element-update',
                    ],
                ]
            );
        } else {
            $builder->add(
                'update',
                SubmitType::class,
                [
                    'label_format' => $options['entity'] !== 'activity' ? 'activity_elements.save' : ('activity_elements.%name%'),

                    'attr' => [
                        'class' => 'btn-large waves-effect waves-light blue darken-4 activity-element-update',
                    ],
                ]
            );
        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'entity' => 'iprocess',
                'data_class'   => static function (Options $options) {
                    switch ($options['entity']) {
                        case 'iprocess':
                            return InstitutionProcess::class;
                            break;
                        case 'process':
                            return Process::class;
                            break;
                        case 'activity':
                            return Activity::class;
                            break;
                    }
                },
                'organization' => null,
            ]);
        $resolver->setRequired("currentUser");
    }
}
