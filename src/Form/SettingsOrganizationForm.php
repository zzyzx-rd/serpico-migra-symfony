<?php

namespace App\Form;

use App\Form\Type\OptionType;
use App\Entity\Organization;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingsOrganizationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('aliveOptions', CollectionType::class,
            [
                'label' => false,
                'entry_type' => OptionType::class,
                'entry_options' => [
                    'label' => false,
                ],
                'prototype' => false,
                'by_reference' => false,
                'allow_delete' => 'false',
                'allow_add' => 'false',
                'label' => false,
            ]
        );

        if ($options['standalone']) {
            $builder->add('submit', SubmitType::class,
                [
                    'label_format' => 'firm_settings_old.%name%',
                    'attr' => [
                        'class' => 'btn waves-effect waves-light teal lighten-1 firm-settings-submit',
                        'style' => 'position:absolute; display:inline-block;right:2%',
                    ],
                ]
            );
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');
        $resolver->setDefault('data_class', Organization::class);
    }
}
