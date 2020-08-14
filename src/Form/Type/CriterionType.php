<?php
namespace App\Form\Type;

use Controller\MasterController;
use Doctrine\ORM\EntityRepository;
use App\Entity\ProcessCriterion;
use App\Entity\TemplateCriterion;
use App\Entity\Criterion;
use App\Entity\CriterionName;
use App\Entity\IProcessCriterion;
use App\Entity\Organization;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Validator\Step;
use Validator\UBGreaterThanLB;


class CriterionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Organization */
        $organization = $options['organization'];

        $currentUser = MasterController::getAuthorizedUser();
        if (!$currentUser instanceof User) {
            throw 'Authentication issue: no authorized user found';
        }

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($organization, $currentUser) {
            $form = $event->getForm();

            $form->add('cName', EntityType::class,
                [
                    'label_format' => 'criteria.criterion.%name%',
                    'class' => CriterionName::class,
                    'choice_label' => function(CriterionName $c) {
                        $iconObj = $c->getIcon();
                        $icon = ($iconObj and $iconObj->getType() != 'm') ? '~'.$iconObj->getUnicode().'~ ' : '~f1b2~ ';
                        return $icon . $c->getName();
                    },               
                    'choice_attr' => function(CriterionName $c) {
                        $iconObj = $c->getIcon();
                        return $iconObj ? 
                            [
                                'class' => ($iconObj->getType() != 'm') ? $c->getIcon()->getType() . ' fa-'.$c->getIcon()->getName() : $c->getIcon()->getName(), 
                                'data-icon' => ($iconObj->getType() != 'm') ? $iconObj : '',
                            ]
                            : [];
                    },       
                    'group_by' => 'criterionGroup',
                    'query_builder' => function(EntityRepository $er) use ($organization, $currentUser) {
                        $orgId = $organization
                                 ? $organization->getId()
                                 : $currentUser->getOrganization()->getId();
                        return $er->createQueryBuilder('cn')
                            ->where("cn.organization = $orgId")
                            ->orderBy('cn.type', 'asc')
                            ->addOrderBy('cn.id', 'asc');
                    },
                    'attr' => [
                        'class' => 'select-with-fa'
                    ]
                ]
            );
        });

        $builder
        ->add('type', ChoiceType::class,
            [
                'label_format' => 'criteria.criterion.%name%',
                'choices' => [
                    'create_parameters.type_absval_option' => 1,
                    'create_parameters.type_feedback_option' => 2,
                    'create_parameters.type_binary_option' => 3
                ],
                'expanded' => true,
                'multiple' => false,
                'attr' => ['class' => 'gradetype', 'id' => 'gradeId']
            ]
        )
        ->add('lowerbound', NumberType::class,
            [
                'constraints' => [
                    new Assert\NotBlank,
                    new Assert\GreaterThanOrEqual(0)
                ],
                'scale' => 1,
                'label_format' => 'criteria.criterion.%name%',
                'attr' => [ 'class' => 'lowerbound' ],
                'empty_data' => 0,
            ]
        )
        ->add('upperbound', NumberType::class,
            [
                'constraints' => [
                    new Assert\NotBlank,
                    new UBGreaterThanLB
                ],
                'scale' => 1,
                'label_format' => 'criteria.criterion.%name%',
                'attr' => [ 'class' => 'upperbound' ],
            ]
        )
        ->add('step', NumberType::class,
            [
                'constraints' => [
                    new Assert\NotBlank,
                    new Assert\GreaterThan(0),
                    new Step
                ],
                'scale' => 2,
                'label_format' => 'criteria.criterion.%name%',
                'attr' => [ 'class' => 'step' ],
            ]
        )
        ->add('forceCommentCompare', CheckboxType::class,
            [
                'attr' => [ 'class' => 'filled-in forceCommentCompare' ],
                'label_format' => 'criteria.criterion.%name%',
                'required' => true
            ]
        )
        ->add('forceCommentSign', ChoiceType::class,
            [
                'attr' => [
                    'class' => 'forceCommentSign',
                    'style' => 'position: relative;margin-bottom:0px'
                ],
                'label' => null,
                'choices' => [
                    'criteria.criterion.force_comments_strictly_lower_than' => 'smaller',
                    'criteria.criterion.force_comments_lower_than_equal' => 'smallerEqual'
                ]
            ]
        )
        ->add('forceCommentValue', NumberType::class,
            [
                'label_format' => 'criteria.criterion.%name%',
                'label_attr' => [ 'class' => 'active' ],
                'attr' => [
                    'class' => 'forceCommentValue',
                    'style' => 'margin-bottom:0px'
                ],
            ]
        )
        ->add('targetValue', PercentType::class,
            [
                'label_format' => 'criteria.criterion.%name%',
                'constraints' => [
                    new Assert\GreaterThanOrEqual(0)
                ],
                'attr' => [
                    'style' => 'margin-bottom:0px'
                ]
            ]
        )
        ->add('comment', TextareaType::class,
            [
                'label_format' => 'criteria.criterion.%name%',
            ]
        );

        //if(!$options['standalone']){
            $builder->add('weight',PercentType::class,
            [
                'label_format' => 'criteria.criterion.%name%',
                'label_attr' => [ 'class' => 'active' ],
                'attr' => [ 'class' => 'weight-input' ],
                'constraints' => [
                    new Assert\GreaterThanOrEqual(0)
                ]
            ]);
        //}
    }

    public function getParent()
    {
        return FormType::class;
    }

    public function getName(){
        return 'criterion';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('elmtType', 'activity');
        $resolver->setDefault('data_class',function (Options $options){
            if($options['elmtType'] == 'template'){
                return TemplateCriterion::class;
            } else if($options['elmtType'] == 'iprocess'){
                return IProcessCriterion::class;
            } else if($options['elmtType'] == 'process'){
                return ProcessCriterion::class;
            } else {
                return Criterion::class;
            }
        });
        $resolver->setDefault('app', null);
        $resolver->setDefault('organization', null);
        $resolver->setDefault('parentBuilder', null);
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');
    }

}
