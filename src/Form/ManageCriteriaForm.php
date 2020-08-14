<?php
namespace App\Form;

use App\Form\Type\CriterionGroupType;
use App\Entity\Organization;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ManageCriteriaForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    /**
     * @var Organization
     */
    $organization = $options['data'];

    $builder
      ->add(
        'criterionGroups', CollectionType::class,
        [
          'entry_type' => CriterionGroupType::class,
          'entry_options' => [
            'departments' => $organization->getDepartments()
          ],
          'prototype' => true,
          'by_reference' => false,
          'allow_delete' => true,
          'allow_add' => true,
          'label' => false
        ]
      );
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver
    ->setDefaults([
      'data_class' => Organization::class
    ]);
  }
}
