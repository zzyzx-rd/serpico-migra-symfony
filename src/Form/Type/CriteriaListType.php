<?php

namespace App\Form\Type;

use App\Entity\User;
use App\Entity\CriterionName;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Controller\MasterController;

class CriteriaListType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {

    $organization = $options['organization'];

    $cNames = [];
    foreach($organization->getCriterionNames() as $cName){
      $cNames[$cName->getName()] = $cName->getName();
    }

    $builder

      ->add('name', ChoiceType::class, [

        'choices' => $cNames,
        'expanded' => false,
        'multiple' => false,
        'label_format' => null,
        'required' => true,
        'placeholder' => false,
    ])
      ->add('unit', ChoiceType::class, [

        'choices' => [
            '€' => '€',
            '$' => '$',
            '%' => '%'
        ],
        'expanded' => false,
        'multiple' => false,
        'label_format' => null,
        'required' => false,
        'placeholder' => 'criterion_groups.criterion_name.no_unit',
    ]);
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'data_class' => CriterionName::class
    ])
    ->setRequired([
      'organization'
    ]);
  }
}
