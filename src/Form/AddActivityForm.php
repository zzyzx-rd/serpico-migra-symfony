<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etudiant
 * Date: 13/12/2017
 * Time: 17:31
 */

namespace App\Form;

use App\Entity\Criterion;
use App\Entity\Activity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;
use Validator\EDGreaterThanSD;
use Validator\UBGreaterThanLB;
use Validator\Step;

class AddActivityForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $defaultStartDate = new \DateTime;
        $defaultEndDate = $defaultStartDate->add(new \DateInterval('P30D'));

        $builder->add('name', TextType::class,
            [
                'label' => 'Activity name',
                'constraints' => [
                    new Assert\NotBlank
                ]
            ])
            ->add('visibility', ChoiceType::class,
                [   'choices' => [
                        'Public (within the organization)' => true,
                        'Private' => false
                    ],
                    'label' => 'Visibility',
                    'expanded' => true,
                    'multiple' => false,
                    'choices_as_values' => true,
                    'data' => true
                ])

            ->add('startdate', DateTimeType::class, [
                'format' => 'dd MMMM, yyyy',
                'widget' => 'single_text',
                'label' => 'Startdate',
                'html5' => false,
                'attr' => ['class' => 'datepicker-start'],
                //'data' => new \DateTime,

                'constraints' => [
                    new Assert\NotBlank,
                ]
            ])

            ->add('enddate', DateTimeType::class, [
                'format' => 'dd MMMM, yyyy',
                'widget' => 'single_text',
                'label' => 'Estimated enddate',
                'html5' => false,
                'attr' => ['class' => 'datepicker-end'],
                //'data' => $defaultEndDate,

                'constraints' => [
                    new Assert\NotBlank,
                    new EDGreaterThanSD
                ]
            ])

            ->add('gradetype', ChoiceType::class,
                [
                    'label' => 'Grading Method',
                    'choices' => [
                        'Absolute' => true,
                        'Relative' => false
                    ],
                    'expanded' => true,
                    'multiple' => false,
                    'choices_as_values' => true,
                    'data' => true
                ])

            ->add('lowerbound', NumberType::class,
                [
                    'constraints' => [
                        new Assert\NotBlank([
                            'message' => '*Lowerbound parameter is mandatory'
                        ]),
                        new Assert\GreaterThanOrEqual([
                            'value' => 0,
                            'message' => '*Lowerbound should be superior or equal to 0'
                        ])
                    ],
                    'scale' => 1,
                    'label' => 'Lowerbound',
                ])

            ->add('upperbound', NumberType::class,
                [
                    'constraints' => [
                        new Assert\NotBlank([
                            'message' => 'Upperbound parameter is mandatory'
                        ]),
                        new UBGreaterThanLB
                    ],
                    'scale' => 1,
                    'label' => 'Upperbound'
                ])

            ->add('step', NumberType::class,
                [
                    'constraints' => [
                        new Assert\GreaterThan([
                            'value' => 0,
                            'message' => 'Step should be superior or equal to 0'
                        ]),
                        new Step
                        /*new Assert\LessThanOrEqual([
                            'value' => 'lb',
                            'message' => '*Step should be superior or equal to 0'
                        ])*/

                    ],
                    'scale' => 2,
                    'label' => '(Optional) Min increment',
                ])

            ->add('objectives', TextareaType::class,
                [

                    'label' => 'Activity objectives & remarks (visible to every participant)',
                    'attr' => [
                        'class' => 'activity-objectives'
                    ]
                ])

            ->add('weight', TextType::class,
                [
                    'disabled' => true,
                    'data' => '100%'
                ]);

        if ($options['standalone']){
            $builder->add('submit', SubmitType::class,[
                'label' => 'Next : Define stages'
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class',Activity::class);
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');
    }
}
