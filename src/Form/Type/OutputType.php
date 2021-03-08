<?php


namespace App\Form\Type;


use App\Entity\Answer;
use App\Entity\CriterionName;
use App\Entity\Organization;
use App\Entity\Output;
use App\Validator\Step;
use App\Validator\SumWeightEqualToHundredPct;
use App\Validator\UBGreaterThanLB;
use App\Validator\UniqueStageName;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class OutputType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Organization */
        $organization = $options['organization'];

        $currentUser =$options['currentUser'];


        $builder
            ->add('type', ChoiceType::class,
                [

                    'choices' => ['critère'=>1,
                        'survey'=>2],
                    'expanded' => true,
                    'required' => true,
                    'multiple' => false,

                ]
            );

        $builder->add('criteria', CollectionType::class,
            [
                'entry_type' => CriterionType::class,
                'entry_options' => [
                    'currentUser' => $options["currentUser"],
                    'organization' => $options['organization'],
                    'entity' => $options['entity'],
                ],
                'prototype' => true,
                'prototype_name' => '__otpIndex__',
                'by_reference' => false,
                'allow_delete' => 'true',
                'allow_add' => 'true',
                'constraints' => [
                    new NotNull,
                    new SumWeightEqualToHundredPct,
                ],
                'label' => false,
                'error_bubbling' => false,
            ]);
        $builder->add('startdate', DateTimeType::class,
            [
                'format' => 'Y-m-d H:i:s',
                'widget' => 'single_text',
                'label_format' => 'stages.stage.%name%',
                'html5' => false,
                'attr' => ['class' => 'dp-start'],
                //'data' => $options['startdate'],

            ])

            ->add('enddate', DateTimeType::class,
                [
                    'format' => 'Y-m-d H:i:s',
                    'widget' => 'single_text',
                    'label_format' => 'stages.stage.%name%',
                    'html5' => false,
                    'attr' => ['class' => 'dp-end'],
                    //'data' => $options['enddate'],

                ]);
        $builder->add('visibility', ChoiceType::class,
            [
                'label' => 'visibilé',
                'choices' => [
                    'public' => 1,
                    'private' => 2,

                ],
                'required' => true,
            ]);


        //}
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('entity', 'activity');
        $resolver->setDefaults([
            'data_class' => Output::class,
        ]);
        $resolver->setRequired('currentUser');
        $resolver->setDefault('organization', null);
        $resolver->setDefault('standalone', false);
    }
}
