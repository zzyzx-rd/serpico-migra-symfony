<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etudiant
 * Date: 07/12/2017
 * Time: 09:35
 */
namespace App\Form;

use App\Entity\Organization;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\Type\UserType;

class AddUserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder->add('usersCSV', FileType::class,[
            //'label' => 'Upload multiple users (CSV file)',
            'required' => false,
            'attr' => [
                'class' => 'dropify',
                'data-allowed-file-extensions' => 'csv',
                'data-max-file-size' => '1M',
                'data-height' => '200'
            ],
            'invalid_message' => 'create_user.csv_sub_error',
        ])
        


            ->add('users', CollectionType::class,
            [
                'entry_type' => UserType::class,
                'entry_options' => [
                    'organization' => $options['organization'],
                    'enabledCreatingUser' => $options['enabledCreatingUser'],
                    'standalone' => false,
                ],
                'prototype'    => true,
                'by_reference' => false,
                'allow_delete' => true,
                'allow_add' => true,
                'label' => false,

            ]);

        if ($options['standalone']){
            $builder->add('submit', SubmitType::class,[
                'label_format' => 'create_user.%name%',
                'attr' =>
                    [
                        'class' => 'waves-effect waves-light btn-large blue darken-4 create-users',
                    ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        //$resolver->setDefault('data_class',Organization::class);
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');
        $resolver->setDefault('organization', null);
        $resolver->setDefault('enabledCreatingUser', false);
    }

}
