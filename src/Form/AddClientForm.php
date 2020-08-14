<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etudiant
 * Date: 07/12/2017
 * Time: 09:35
 */
namespace App\Form;

use App\Form\Type\ClientType;
use App\Form\Type\ExternalUserType;
use App\Entity\Organization;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\Type\UserType;

class AddClientForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder->add('clients', CollectionType::class,
        [
            'entry_type' => ClientType::class,
            'entry_options' => [
                'organization' => $options['organization'],
                'standalone' => false,
                'hasChildrenElements' => true,
            ],
            'prototype'    => true,
            'by_reference' => false,
            'allow_delete' => 'true',
            'allow_add' => 'true',
            'label' => false,

        ]);


        if ($options['standalone']){
            $builder->add('submit', SubmitType::class,[
                'label_format' => 'create_client.%name%',
                'attr' =>
                    [
                        'class' => 'waves-effect waves-light btn-large blue darken-4 create-external-users',
                    ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');
        $resolver->setDefault('organization', null);
    }

}
