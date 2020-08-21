<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 28/12/2017
 * Time: 15:25
 */

namespace App\Form\Type;

use App\Form\Type\SurveyFieldParameterType;

use App\Entity\Survey;
use App\Entity\SurveyField;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormTypeInterface;
use App\Entity\ActivityUser;
use App\Entity\Grade;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use App\Entity\Stage;
use Symfony\Component\Validator\Constraints\NotBlank;
use App\Validator\ForcedComment;
use App\Validator\UniquePerOrganization;

class SurveyFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
                $builder->add('type', ChoiceType::class,
                    [
                        'label' => 'choix type question',
                        'choices' => [
                            'Réponse courte' => 'ST',
                            'Paragraphe' => 'LT',
                            'Choix binaire' => 'UC',
                            'Case à cocher' => 'MC',
                            'Choix multiples' => 'SC',
                            'Echelle linéaire' => 'LS',
                        ],
                        'required' => true,
                    ]);

                $builder->add('isMandatory', CheckboxType::class,
                    [
                        'attr' => ['class' => 'filled-in','required'],
                        'label' => false,
                        'required' => false,
                    ]);

                $builder->add('title', TextType::class,
                    [
                        'label' => 'titre de la question',
                        'constraints' => [
                            new NotBlank,
                        ],
                        'required' => true,
                    ]);

                $builder->add('description', TextType::class,
                    [
                        'label' => 'description de la question',
                        'required' => false,
                    ]);


                $builder->add('parameters', CollectionType::class,
                    [
                        'entry_type' => SurveyFieldParameterType::class,
                        'prototype' => true,
                        'by_reference' => false,
                        'allow_delete' => true,
                        'allow_add' => true,
                        'label' => false,
                        'prototype_name' => '__pname__'
                    ]);

        $builder->add('lowerbound', ChoiceType::class,
            [
                'label' => 'min',
                'choices' => [
                    '0' => 0,
                    '1' => 1,

                ],
                'empty_data' => '0',
                'required' => false
            ]);
        $builder->add('upperbound', ChoiceType::class,
            [
                'label' => 'max',
                'choices' => [
                    '9' => 9,
                    '10' => 10,

                ],
                'empty_data' => '10',
                'required' => false
            ]);




    }


    public function getParent()
    {
        return FormType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SurveyField::class,
            'edition' => true,
        ]);
    }

}
