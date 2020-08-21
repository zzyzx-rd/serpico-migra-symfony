<?php
/**
 * Created by IntelliJ IDEA.
 * User: lawre
 * Date: 14/05/2018
 * Time: 10:06
 */

namespace App\Form;


use App\Entity\Organization;
use App\Entity\Process;
use App\Form\Type\ProcessType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ManageProcessForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

            $elements = ($options['isRoot']) ? 'parentValidatedProcesses' : 'parentValidatedInstitutionProcesses';
            $organization = $builder->getData();

            $builder->add($elements, CollectionType::class,
            [
                'entry_type' => ProcessType::class,
                'entry_options' => [
                    'organization' => $organization,
                    'isRoot' => $options['isRoot'],
                    'standalone' => true,
                    'label' => false,
                ],
                'prototype'    => true,
                'by_reference' => false,
                'allow_delete' => 'true',
                'allow_add' => 'true',
                'label' => false,

            ]);

        if ($options['standalone']){
            $builder->add('submit', SubmitType::class,[
                'label_format' => 'organization_departments.%name%',
                /*'attr' =>
                    [
                        'class' => 'waves-effect waves-light btn-large blue darken-4 create-targets',
                    ]*/
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('isRoot', false);
        $resolver->setDefault('data_class', Organization::class);
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');
    }
    
}