<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 07/01/2018
 * Time: 13:23
 */

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Criterion;
use Symfony\Component\OptionsResolver\OptionsResolver;


class CriterionParticipantType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('participants', CollectionType::class,
            [
                'entry_type' => ParticipantType::class,
                'entry_options' => [
                    'organization' => $options['organization'],
                ],
                'prototype' => true,
                'by_reference' => false,
                'allow_delete' => 'true',
                'allow_add' => 'true',
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', Criterion::class);
        $resolver->setDefault('organization', null);
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');
    }

}
