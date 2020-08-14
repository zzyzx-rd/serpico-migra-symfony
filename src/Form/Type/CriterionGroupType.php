<?php

namespace App\Form\Type;

use Controller\MasterController;
use Doctrine\ORM\PersistentCollection;
use App\Entity\CriterionGroup;
use App\Entity\Department;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CriterionGroupType extends AbstractType
{
  function buildForm(FormBuilderInterface $builder, array $options)
  {
    /**
     * @var Department[]
     */
    $departments = $options['departments'];
    
    $builder
      ->add(
        'name', TextType::class,
        [
          'label_format' => 'organization_criteriongroups.labels.criteriongroup_name_discardable'
        ]
      )
      ->add(
        'department', EntityType::class,
        [
          'label_format' => 'organization_criteriongroups.labels.cgp_department',
          'placeholder' => 'organization_criteriongroups.no_linked_dpt_placeholder',
          'class' => Department::class,
          'choices' => $departments,
          'required' => false,
          'attr' => [
            'class' => 'criteriongroup-department-select'
          ]
        ]
      )
      ->add(
        'criteria', CollectionType::class,
        [
          'entry_type' => CriteriaListType::class,
          'entry_options' => [
            'organization' => count($departments) ? $departments->first()->getOrganization() : 
            MasterController::getAuthorizedUser()->getOrganization(),
          ],
          'prototype' => true,
          'by_reference' => false,
          'allow_delete' => true,
          'allow_add' => true,
          'label' => false,
          'required' => false
        ]
      );
  }

  function configureOptions(OptionsResolver $resolver)
  {
    $resolver
    ->setRequired([
      'departments'
    ])
    ->setAllowedTypes('departments', PersistentCollection::class)
    ->setDefaults([
      'data_class' => CriterionGroup::class
    ]);
  }
}
