<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etudiant
 * Date: 07/12/2017
 * Time: 09:35
 */
namespace App\Form;


use App\Form\ValidateFirmForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints as Assert;

class ValidateMassFirmForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('firms', CollectionType::class, [
            'entry_type' => ValidateFirmForm::class,
                'prototype'    => false,
                'by_reference' => false,
                'allow_delete' => 'false',
                'allow_add' => 'false',
                'data' => $options['firms']
            ]);

        if($options['searchByLocation'] == 0){

            $builder->add('createLocOtherFirms', CheckboxType::class, [
                'data' => true,
                'label_format' => 'validate_mass_firm.%name%',
                'required' => true,
                'attr' => [
                    'class' => 'filled-in'
                ]
                ]);

        }
            
            

        if ($options['standalone']){
            $builder->add('submit', SubmitType::class,[
                'label_format' => 'validate_mass_firm.%name%',
                'attr' =>
                    [
                        'class' => 'validate-mass-firm waves-effect waves-light btn-large blue darken-4',
                    ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('standalone', false);
        $resolver->setDefault('searchByLocation', false);
        $resolver->addAllowedTypes('standalone', 'bool');
        $resolver->setDefault('firms', false);
    }

}
