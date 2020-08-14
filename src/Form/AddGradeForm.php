<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 27/12/2017
 * Time: 16:11
 */

namespace App\Form;

use App\Entity\Activity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class AddGradeForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $today = new \DateTime;

        $builder->add('grades', CollectionType::class, [
        'entry_type' => GradeType::class,

            'entry_options' => [
            ],


            'prototype'    => false,
            'by_reference' => false,
            'allow_delete' => 'true',
            'allow_add' => 'true'

        ]);

        if ($options['standalone']) {
            $builder->add('submit', SubmitType::class, [
                'label' => 'Save'
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
