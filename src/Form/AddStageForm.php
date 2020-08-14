<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 27/12/2017
 * Time: 16:11
 */

namespace App\Form;


use App\Entity\Activity;
use App\Entity\TemplateActivity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Twig\Extension\StagingExtension;
use App\Form\Type\StageType;
use Validator\SumWeightEqualToHundredPct;



class AddStageForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $today = new \DateTime;

        $builder->add('activeModifiableStages', CollectionType::class, [
        'entry_type' => StageType::class,

            'entry_options' => [
                'elmt' => $options['elmt'],
            //'startdate' => $today,
            //'enddate' => $today->add(new \DateInterval('P30D')),
            //'gstartdate' => $today->add(new \DateInterval('P30D')),
            //'genddate' => $today->add(new \DateInterval('P37D')),
            ],


            'prototype'    => true,
            'by_reference' => false,
            'allow_delete' => 'true',
            'allow_add' => 'true',
            'constraints' => [
                new SumWeightEqualToHundredPct,
            ],

        ]);

        if ($options['standalone']) {
            $builder->add('previous', SubmitType::class, [
                'label_format' => 'stages.%name%',
                'attr' => [
                    'class' => 'btn-large waves-effect waves-light blue darken-4 prev-button',
                ]
            ])
            ->add('back', SubmitType::class, [
                'label_format' => 'stages.%name%',
                'attr' => [
                    'class' => 'btn-large waves-effect waves-light teal lighten-1 back-button',
                ]
            ])
            ->add('next', SubmitType::class, [
                'label_format' => 'stages.%name%',
                'attr' => [
                    'class' => 'btn-large waves-effect waves-light blue darken-4 next-button',
                ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('elmt', 'activity');
        $resolver->setDefault('data_class',function (Options $options){
            if($options['elmt'] == 'template'){
                return TemplateActivity::class;
            }
            return Activity::class;
        });
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');
    }

}
