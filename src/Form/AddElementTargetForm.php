<?php

namespace App\Form;

use App\Form\Type\TargetType;
use App\Entity\Criterion;
use App\Entity\Department;
use Model\Organization;
use Model\Position;
use Model\Team;
use Model\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddElementTargetForm extends AbstractType
{
    private const CLASSES = [
        'user'         => User::class,
        'team'         => Team::class,
        'department'   => Department::class,
        'position'     => Position::class,
        'organization' => Organization::class,
        'criterion'    => Criterion::class,
    ];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'targets', CollectionType::class,
            [
                'label'         => false,
                'entry_type'    => TargetType::class,
                'entry_options' => [
                    'label'        => false,
                    'organization' => $options['organization'],
                ],
                'prototype'     => true,
                'by_reference'  => false,
                'allow_delete'  => 'true',
                'allow_add'     => 'true',
            ]
        );

        if ($options['standalone']) {
            $builder->add(
                'submit', SubmitType::class,
                [
                    'label_format' => 'targets.%name%',
                    'attr'         => [
                        'class' => 'waves-effect waves-light btn-large blue darken-4 create-targets',
                    ],
                ]
            );
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('elmtType', 'user');
        $resolver->setDefault('data_class', function (Options $options) {
            $elmtType = $options['elmtType'];
            return self::CLASSES[$elmtType];
        });
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');
        $resolver->setDefault('organization', null);
        $resolver->setDefault('app', null);
    }
}
