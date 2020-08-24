<?php

/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 27/12/2017
 * Time: 16:11
 */

namespace App\Form;


use App\Form\Type\AnswerType;
use App\Form\Type\ProcessType;
use App\Form\Type\StageUniqueParticipationsType;
use App\Form\Type\SurveyFieldType;
use App\Entity\Activity;
use App\Entity\Answer;
use App\Entity\Survey;
use App\Entity\TemplateActivity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Twig\Extension\StagingExtension;
use App\Form\Type\StageCriterionType;
use App\Entity\Participation;
use App\Validator\UniquePerOrganization;

class AddSurveyForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $edition = $options['edition'];
        $survey = $options['survey'];
        $user = $options['user'];

        if ($edition) {
            $builder->add(
                'name',
                TextType::class,
                [
                    'label' => 'Titre du survey',
                    'empty_data' => false,
                    'required' => false,
                    'attr' => [
                        'class' => 'surveyTitleLabel'
                    ]
                ]
            );

            $builder->add('fields', CollectionType::class, array(
                'entry_type' => SurveyFieldType::class,
                'prototype'    => true,
                'by_reference' => false,
                'allow_delete' => true,
                'allow_add' => true,
                'label' => false,
            ));

            $builder->add(
                'submit',
                SubmitType::class,
                [
                    'label_format' => 'process_stages.surveySaveButton',
                    'attr' =>
                        [
                            'class' => 'btn waves-effect waves-light',
                        ]
                ]
            );
        } else {

            $builder->add(
                'Useranswers',
                CollectionType::class,
                [
                    'label' => false,
                    'entry_type' => AnswerType::class,
                    'prototype'    => true,
                    'by_reference' => false,
                    'allow_add' => true,
                    'allow_delete' => true,
                ]
            );
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Survey::class,
            'edition' => true,
            'survey' => new Survey,
            'user' => new Participation,
        ]);
    }
}
