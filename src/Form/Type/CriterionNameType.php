<?php

namespace App\Form\Type;

use App\Entity\CriterionName;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Validator\UniquePerOrganization;
use Symfony\Component\Validator\Constraints as Assert;

class CriterionNameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name', TextType::class, [
            'constraints' => [
                new Assert\NotBlank,
                new UniquePerOrganization([
                    'organization' => $options['organization'],
                    'entity' => 'activity',
                    'element' => $builder->getData(),
                    'property' => 'name'
                ])
            ],
            'label_format' => 'criteria.create_criterion.%name%'
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'OK',
            'attr' => [ 'class' => 'btn' ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('organization');
        $resolver->setDefault('data_class', CriterionName::class);
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');
    }
}
