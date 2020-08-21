<?php
/**
 * Created by IntelliJ IDEA.
 * User: lawre
 * Date: 14/05/2018
 * Time: 10:06
 */

namespace App\Form;


use App\Entity\Organization;
use App\Entity\User;
use App\Form\Type\DepartmentType;
use App\Form\Type\CriterionNameType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ManageCriterionNameForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

            $builder->add('criterionNames', CollectionType::class,
            [
                'entry_type' => CriterionNameType::class,
                'entry_options' => [
                    //'user' => $options['organization'],
                    //'organization' => $options['organization'],
                    //'enabledCreatingUser' => $options['enabledCreatingUser'],
                ],
                'prototype'    => true,
                'by_reference' => false,
                'allow_delete' => 'true',
                'allow_add' => 'true',
                //'label' => 'Insert new users (already '.$options['nbweights'].' voting powers attributed totalling '.round($options['totalweights'],1). ' weights)'

            ]);

        if ($options['standalone']){
            $builder->add('submit', SubmitType::class,[
                'label_format' => 'organization_criterion_names.%name%',
                'attr' =>
                    [
                        'class' => 'waves-effect waves-light btn-large blue darken-4 create-targets',
                    ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class',Organization::class);
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');
        $resolver->setDefault('organization', null);
    }
    
}