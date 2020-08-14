<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etudiant
 * Date: 07/12/2017
 * Time: 09:35
 */
namespace App\Form;

use App\Entity\WorkerFirm;
use App\Form\ValidateMailForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints as Assert;

class ValidateMassMailForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('workingIndividuals', CollectionType::class, [
            'entry_type' => ValidateMailForm::class,
                'prototype'    => false,
                'by_reference' => false,
                'allow_delete' => 'false',
                'allow_add' => 'false',
            ]);




        if ($options['standalone']){
            $builder->add('submit', SubmitType::class,[
                'label_format' => 'validate_mass_mail.%name%',
                'attr' =>
                    [
                        'class' => 'validate-mass-mail waves-effect waves-light btn-large blue darken-4',
                    ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');
    }

}
