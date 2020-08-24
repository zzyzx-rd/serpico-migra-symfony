<?php
namespace App\Form;

use Doctrine\ORM\EntityRepository;
use App\Entity\Activity;
use App\Entity\CriterionName;
use App\Entity\OrganizationUserOption;
use App\Entity\TemplateActivity;
use Symfony\Bridge\Doctrine\App\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\EDGreaterThanSD;
use App\Validator\GEDGreaterThanED;
use App\Validator\GEDGreaterThanGSD;
use App\Validator\GSDGreaterThanSD;
use App\Validator\UniquePerOrganization;
use App\Validator\Step;
use App\Validator\UBGreaterThanLB;

class AddActivityCriteriaForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Activity|TemplateActivity */
        $activity = $options['activity'];
        $isTemplate = $activity instanceof TemplateActivity;
        $firstStage = $activity->getStages()->first();
        if ($firstStage) {
            $firstCriterion = $firstStage->getCriteria()->first();
        }
        $activityComplex = (count($activity->getStages()) > 1 || count($activity->getStages()->first()->getCriteria()) > 1);

        $organization = $options['organization'];

        $builder
        ->add('name', TextType::class,
            [
                'label_format' => $isTemplate ? 'create_parameters.template_%name%' : 'create_parameters.activity_%name%',
                'constraints' => [
                    new Assert\NotBlank,
                    new UniquePerOrganization([
                        'organization' => $organization,
                        'entity' => 'activity',
                        'element' => $activity,
                        'property' => 'name',
                        ]
                    ),
                ],
                'data' => $activity->getName()
            ]
        )
        ->add('magnitude', IntegerType::class,
            [
                'label_format' => 'create_parameters.%name%',
                'constraints' => [
                    new Assert\NotBlank
                ],
                'data' => ($activity->getMagnitude()) ?: 1
            ]
        )
        ->add('visibility', ChoiceType::class,
            [
            'choices' => [
                'create_parameters.visibility_public_option' => true,
                'create_parameters.visibility_private_option' => false
            ],
            'label_format' => 'create_parameters.%name%',
            'expanded' => true,
            'multiple' => false,
            'choices_as_values' => true,
            'data' =>  ($activity->getVisibility() == true) ?: true,
        ]);

        if (!$activityComplex) {
            $builder
            ->add('mode', ChoiceType::class,
                [
                    'label_format' => 'create_parameters.%name%',
                    'choices' => [
                        'create_parameters.graded_activity_mode' => 0,
                        'create_parameters.graded_participant_mode' => 1,
                        'create_parameters.graded_activity_and_participant_mode' => 2
                    ],
                    'expanded' => true,
                    'multiple' => false,
                    'choices_as_values' => true,
                    'data' => $firstStage->getMode(),
                ]
            )
            ->add('type', ChoiceType::class,
                [
                    'label_format' => 'create_parameters.%name%',
                    'choices' => [
                        'create_parameters.type_absval_option' => 1,
                        'create_parameters.type_feedback_option' => 2,
                        'create_parameters.type_binary_option' => 3,
                    ],
                    'expanded' => true,
                    'multiple' => false,
                    'choices_as_values' => true,
                    'data' => ($firstStage) ? $firstCriterion->getType() : 1,
                ]
            )
            ->add('cName', EntityType::class,
                [
                    'label_format' => 'criteria.criterion.%name%',
                    'class' => CriterionName::class,
                    'choice_label' => function(CriterionName $c) {
                        $iconObj = $c->getIcon();
                        $icon = $iconObj ? '~'.$iconObj->getUnicode().'~ ' : '';
                        return $icon . $c->getName();
                    },
                    'group_by' => 'criterionGroup',
                    'query_builder' => function(EntityRepository $er) use ($organization) {
                        $orgId = $organization->getId();
                        $canLeaderSelectCrtOutsideSkillMatrix = $organization->getOptions()->filter(function (OrganizationUserOption $o) { return $o->getOName()->getName() == 'enabledCNamesOutsideCGroups'; })->first();
                        if($canLeaderSelectCrtOutsideSkillMatrix->isOptionTrue()){
                            return $er->createQueryBuilder('cn')
                                ->where("cn.organization = $orgId")
                                ->orderBy('cn.type', 'asc')
                                ->addOrderBy('cn.id', 'asc');
                        } else {
                            return $er->createQueryBuilder('cn')
                                ->innerJoin('App\Entity\CriterionGroup', 'cg', 'WITH', 'cn.criterionGroup = cg.id')
                                ->where("cg.organization = $orgId")
                                ->andWhere('cn.criterionGroup is not null')
                                ->orderBy('cn.type', 'asc')
                                ->addOrderBy('cn.id', 'asc');
                        }
                    },
                    'attr' => [
                        'class' => 'select-with-fa'
                    ],
                    'constraints' => [
                        new Assert\NotBlank
                    ],
                    'data' => $firstCriterion->getcName()
                    //'required' => false,
                ]
            )
            ->add('forceCommentCompare', CheckboxType::class,
                [
                    'attr' => ['class' => 'filled-in' ],
                    'label_format' => 'create_parameters.%name%',
                    'required' => true,
                    'data' => ($firstCriterion->getForceCommentValue() !== null) ? true : false,
                ]
            )
            ->add('forceCommentSign', ChoiceType::class,
                [
                    'attr' => [
                            'class' => 'forceCommentSign',
                            'style' => 'position: relative'
                    ],
                    'label' => '   ',
                    'choices_as_values' => true,
                    'choices' => [
                        'create_parameters.force_comments_strictly_lower_than' => 'smaller',
                        'create_parameters.force_comments_lower_than_equal' => 'smallerEqual',
                    ],
                    'data' => ($firstCriterion->getForceCommentSign() !== null) ? $firstCriterion->getForceCommentSign() : 'smaller'
                ]
            )
            ->add('forceCommentValue', NumberType::class,
                [
                    'label_format' => 'create_parameters.%name%',
                    'label_attr' => ['class' => 'active'],
                    'attr' => ['class' => 'forceCommentValue'],
                    'data' => $firstCriterion->getForceCommentValue()
                ]
            )
            ->add('comment', TextareaType::class,
                [
                    'label_format' => 'create_parameters.%name%',
                    'attr' => [
                        'style' => 'min-height:80px',
                    ],
                    'data' => $firstCriterion->getComment()
                ]
            )
            ->add('target', PercentType::class,
                [
                    'label_format' => 'create_parameters.%name%',
                    'constraints' => [
                        new Assert\GreaterThanOrEqual(0),
                    ],
                    'data' => ($firstCriterion->getTarget() != null) ? $firstCriterion->getTarget()->getValue() : 0.7
                ]
            )
            ->add('startdate', DateTimeType::class,
                [
                    'widget' => 'single_text',
                    'label_format' => 'create_parameters.%name%',
                    'html5' => false,
                    'attr' => ['class' => 'dp-start'],
                    'format' => 'dd/MM/yyyy',
                    'data' => ($firstStage) ? $firstStage->getStartDate() : null,
                    'constraints' => [
                        new Assert\NotBlank
                    ]
                ]
            )
            ->add('enddate', DateTimeType::class,
                [
                    //'format' => 'dd/MM/yyyy',
                    //'placeholder' => 'dd/mm/yyyy',
                    'widget' => 'single_text',
                    'label_format' => 'create_parameters.%name%',
                    'html5' => false,
                    'attr' => ['class' => 'dp-end'],
                    'format' => 'dd/MM/yyyy',
                    'data' => ($firstStage) ? $firstStage->getEndDate() : null,
                    'constraints' => [
                        new Assert\NotBlank,
                        new EDGreaterThanSD
                        //new Assert\DateTime(['format' => 'd/m/Y'])
                    ]
                ]
            )
            ->add('gstartdate', DateTimeType::class,
                [
                    //'format' => 'dd/MM/yyyy',
                    //'placeholder' => 'dd/mm/yyyy',
                    'widget' => 'single_text',
                    'label_format' => 'create_parameters.%name%',
                    'html5' => false,
                    'attr' => ['class' => 'dp-gstart'],
                    'format' => 'dd/MM/yyyy',
                    'data' => ($firstStage) ? $firstStage->getGStartDate() : null,
                    'constraints' => [
                        new Assert\NotBlank,
                        new GSDGreaterThanSD
                        //new Assert\DateTime(['format' => 'd/m/Y'])
                    ]
                ]
            )
            ->add('genddate', DateTimeType::class,
                [
                    //'format' => 'dd/MM/yyyy',
                    //'placeholder' => 'dd/mm/yyyy',
                    'widget' => 'single_text',
                    'label_format' => 'create_parameters.%name%',
                    'html5' => false,
                    'attr' => ['class' => 'dp-gend'],
                    'format' => 'dd/MM/yyyy',
                    'data' => ($firstStage) ? $firstStage->getGEndDate() : null,
                    'constraints' => [
                        new Assert\NotBlank,
                        new GEDGreaterThanED,
                        new GEDGreaterThanGSD
                        //new Assert\DateTime(['format' => 'd/m/Y'])
                    ]
                ]
            )
            ->add('lowerbound', NumberType::class,
                [
                    'constraints' => [
                        new Assert\NotBlank,
                        new Assert\GreaterThanOrEqual(0)
                    ],
                    'scale' => 1,
                    'label_format' => 'create_parameters.%name%',
                    'data' => ($firstStage) ? $firstCriterion->getLowerbound() : 0,
                ]
            )
            ->add('upperbound', NumberType::class,
                [
                    'constraints' => [
                        new Assert\NotBlank,
                        new UBGreaterThanLB
                    ],
                    'scale' => 1,
                    'label_format' => 'create_parameters.%name%',
                    'data' => ($firstStage) ? $firstCriterion->getUpperbound() : 5,
                ]
            )
            ->add('step', NumberType::class,
                [
                    'constraints' => [
                        new Assert\NotBlank,
                        new Assert\GreaterThan(0),
                        new Step,
                    ],
                    'scale' => 2,
                    'label_format' => 'create_parameters.%name%',
                    'data' => ($firstStage) ? $firstCriterion->getStep() : 0.5,
                ]
            );
        }

        $builder->add('objectives', TextareaType::class,
            [
                'label_format' => 'create_parameters.%name%',
                'data' => $activity->getObjectives(),
                'attr' => [
                    'class' => 'textarea-broaden'
                ],
                'required' => false
            ]
        );

        if ($options['standalone']) {
            $builder
            ->add('back', SubmitType::class,
                [
                    'label_format' => 'create_parameters.%name%',
                    'attr' => [
                        'class' => 'btn-large waves-effect back-button waves-light teal lighten-1',
                    ]
                ]
            )
            ->add('next', SubmitType::class,
                [
                    'label_format' => 'create_parameters.%name%',
                    'attr' => [
                        'class' => 'btn-large next-button waves-effect waves-light blue darken-4',
                    ]
                ]
            );
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
        ->setRequired([
            'activity',
            'incomplete',
            'organization'
        ])
        ->setDefaults([
            'standalone' => false,
        ])
        ->addAllowedTypes('standalone', 'bool');
    }
}
