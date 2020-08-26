<?php

namespace App\Form;

use App\Form\Type\OrganizationElementType;
use App\Entity\Organization;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ManageOrganizationElementsForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $organization = $builder->getData();

        $elements = $options['elmtType'].'s';

        $builder->add($elements, CollectionType::class,
            [
                'label' => false,
                'entry_type' => OrganizationElementType::class,
                'entry_options' => [
                    'label' => false,
                    'organization' => $organization,
                    'entity' => $options['elmtType'],
                    'standalone' => false,
                ],
                'prototype' => true,
                'by_reference' => false,
                'allow_delete' => true,
                'allow_add' => true,
            ]
        );

        if ($options['standalone']) {
            $builder->add('submit', SubmitType::class,
                [
                    'label_format' => 'organization_departments.%name%',
                ]
            );
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', Organization::class);
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');
        $resolver->setDefault('elmtType', null);
    }
}
