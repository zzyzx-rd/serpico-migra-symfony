<?php

namespace App\Form;

use Controller\MasterController;
use App\Form\Type\MemberType;
use App\Entity\Organization;
use App\Entity\Team;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints as Assert;

class AddTeamForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
   
    $team = $builder->getData();

    $builder->add('name', TextType::class,
        [
            'constraints' => [
                new Assert\NotBlank,
            ],
            'label_format' => 'create_team.%name%',
            'required' => true,
        ])
        
    ->add('currentTeamExtUsers', CollectionType::class,
            [
                'label' => false,
                'entry_type' => MemberType::class,
                'entry_options' => [
                    'organization' => $team->getOrganization(),
                    'query' => 'external',
                    'currentUser' => $options["currentUser"],
                ],
                'prototype' => true,
                'prototype_name' => '__extTUIndex__',
                'by_reference' => false,
                'allow_delete' => true,
                'allow_add' => true,
            ]
        )

    ->add('currentTeamIntUsers', CollectionType::class,
        [
            'label' => false,
            'entry_type' => MemberType::class,
            'entry_options' => [
                'organization' => $team->getOrganization(),
                'query' => 'internal',
                'currentUser' => $options["currentUser"],
            ],
            'prototype' => true,
            'prototype_name' => '__intTUIndex__',
            'by_reference' => false,
            'allow_delete' => true,
            'allow_add' => true,
        ]
        );

    if ($options['standalone']){
        $builder->add('submit', SubmitType::class,[
            'label' => 'Soumettre',
            'attr' => [
                'class' => 'btn-large waves-effect waves-light teal lighten-1',
            ]
        ]);
    }


  }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('standalone', false);
        $resolver->setRequired('currentUser');
        $resolver->addAllowedTypes('standalone', 'bool');
    }
}
