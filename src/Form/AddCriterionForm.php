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
use App\Form\Type\StageCriterionType;
use Validator\AtLeastOneStageHasAOwner;

class AddCriterionForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('activeModifiableStages', CollectionType::class,
            [
                'entry_type' => StageCriterionType::class,
                'entry_options' => [
                    'app' => $options['app'],
                    'organization' => $options['organization'],
                    'elmt' => $options['elmt'],
                ],
                'constraints' => [
                    new AtLeastOneStageHasAOwner ],
                'by_reference' => false,
            ]
        );

        if ($options['standalone']) {
            $builder->add('previous', SubmitType::class, [
                'label_format' => 'criteria.%name%',
                'attr' => [
                    'class' => 'btn-large waves-effect waves-light blue darken-4 prev-button',
                ]
            ])
            ->add('back', SubmitType::class, [
                'label_format' => 'criteria.%name%',
                'attr' => [
                    'class' => 'btn-large waves-effect waves-light teal lighten-1 back-button',
                ]
            ])
            ->add('next', SubmitType::class, [
                'label_format' => 'criteria.%name%',
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
        $resolver->setDefault('multiple_active_stages', false);
        $resolver->setDefault('diff_stages_criteria', false);
        $resolver->setDefault('app', null);
        $resolver->setDefault('organization', null);
    }

}
