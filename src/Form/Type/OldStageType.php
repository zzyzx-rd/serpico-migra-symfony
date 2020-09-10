<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 28/12/2017
 * Time: 15:25
 */

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Stage;
use App\Entity\TemplateStage;
use App\Validator\EDGreaterThanSD;
use App\Validator\GEDGreaterThanED;
use App\Validator\GEDGreaterThanGSD;
use App\Validator\GSDGreaterThanSD;

class OldStageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class,
            [
                'constraints' => [
                    new Assert\NotBlank([
                        //'message' => 'The stage must have a name'
                    ]),
                    /*
                    new Assert\Regex([
                        'pattern' => "/[a-zA-Z]/",
                        'message' => 'Firstname is invalid, please use only letters in this field.'
                    ])
                    */
                ],
                'label_format' => 'stages.stage.%name%',
            ])

            ->add('mode', ChoiceType::class,
                [
                    'label_format' => 'stages.stage.%name%',
                    'choices' => [
                        //'create_parameters.type_relval_option' => 0,
                        'stages.stage.graded_stage_mode' => 0,
                        'stages.stage.graded_participant_mode' => 1,
                        'stages.stage.graded_stage_and_participant_mode' => 2
                    ],
                    'expanded' => true,
                    'multiple' => false,
                    'choices_as_values' => true,
                ]
            )

        ->add('startdate', DateTimeType::class,
            [
                'format' => 'dd/MM/yyyy',
                'widget' => 'single_text',
                'label_format' => 'stages.stage.%name%',
                'html5' => false,
                'attr' => ['class' => 'dp-start'],
                //'data' => $options['startdate'],
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


        ->add('weight',PercentType::class,
        [
            'label_format' => 'stages.stage.%name%',
            'label_attr' => [
                'class' => 'active',
            ],
            'attr' => ['class' => 'weight-input']
        ])
        ;

        /*
        if ($options['standalone']){
            $builder->add('submit', SubmitType::class,[
                'label' => 'Go to next step'
            ]);
        }*/

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('elmt', 'activity');
        $resolver->setDefault('data_class',function (Options $options){
            if($options['elmt'] == 'template'){
                return TemplateStage::class;
            }
            return Stage::class;
        });
        //$resolver->setDefault('standalone', false);
        //$resolver->addAllowedTypes('standalone', 'bool');
        //$resolver->setRequired('startdate');
        //$resolver->setRequired('enddate');
        
    }

    /*

    public function getParent()
    {
        return FormType::class;
    }

    public function getName() {
        return 'stage';
    }*/
}
