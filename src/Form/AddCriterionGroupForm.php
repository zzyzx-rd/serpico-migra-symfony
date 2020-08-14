<?php

namespace App\Form;

use App\Entity\Criterion;
use App\Entity\Department;
use Model\Organization;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

class AddCriterionGroupForm extends AbstractType {
  function buildForm(FormBuilderInterface $builder, array $options)
  {
    /**
     * @var Criterion[]
     */
    $criteria = $options['criteria'];
    /**
     * @var Department[]
     */
    $departments = $options['departments'];
    /**
     * @var Organization[]
     */
    $organizations = $options['organizations'];

    /**
     * @var int
     */
    $userRole = $options['role'];

    $builder
    ->add(
      'name', TextType::class,
      [
        // 'constraints' => [
        //   new Constraints\Regex('^\w$')
        // ]
      ]
    )
    ->add(
      'criteria', ChoiceType::class,
      [
        'choices' => $criteria,
        'choice_label' => function (Criterion $criterion) {
          return $criterion->getName();
        },
        'choice_value' => function (Criterion $criterion) {
          return $criterion->getId();
        },
        'required' => true,
        'expanded' => true,
        'multiple' => true
      ]
    )
    ->add(
      'submit', SubmitType::class,
      []
    );

    switch ($userRole) {
      case 4:
        $builder->add(
          'organization',
          ChoiceType::class,
          [
            'placeholder' => '',
            'required' => true,
            'choices' => $organizations,
            'choice_label' => function (Organization $organization) {
              $commName = $organization->getCommname();
              $legalName = $organization->getLegalname();
              return "$commName ($legalName)";
            },
            'choice_value' => function (Organization $organization) {
              return $organization->getId();
            },
            'choice_attr' => function (Organization $organization) {
              $orgIDs = $organization->getDepartments()->map(
                function(Department $department) {
                  return $department->getId();
                }
              )->toArray();
              return [ 'data-departments' => implode(',', $orgIDs) ];
            }
          ]
        );
      case 1:
        $builder->add(
          'department',
          ChoiceType::class,
          [
            'placeholder' => '',
            'required' => true,
            'choices' => $departments,
            'choice_value' => function (Department $department = null) {
              return $department ? $department->getId() : '';
            },
            'choice_label' => function (Department $department) use ($userRole) {
              $name = $department->getName();
              $organizationName = $department->getOrganization()->getCommname();
              return $userRole == 4 ? "$name ($organizationName)" : $name;
            }
          ]
        );
    }
  }

  function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefault('criteria', []);
    $resolver->setDefault('departments', []);
    $resolver->setDefault('role', 2);
    $resolver->setDefault('organizations', []);
  }
}
