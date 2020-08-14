<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etudiant
 * Date: 07/12/2017
 * Time: 09:35
 */
namespace App\Form\Type;

use Doctrine\ORM\EntityRepository;
use App\Entity\Client;
use App\Entity\Organization;
use App\Entity\User;
use App\Entity\ExternalUser;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints as Assert;
use Validator\UniquePerOrganization;
use Validator\AtLeastOneOwnerAtInception;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $client = $builder->getData();
        $organization = $options['organization'];

    
        $builder->add('commname', TextType::class,
        [
            'label_format' => 'create_client.%name%',
            'required' => false,
            'constraints' => [
                new Assert\NotBlank,
            ],

            //'disabled' => ($user != null && $client->isClient()) ? true : false,
        ])

        ->add('type', ChoiceType::class,
            [
                'constraints' => [
                    new Assert\NotBlank,
                ],
                'label_format' => 'create_client.%name%.label',
                'choices' => [
                    'create_client.type.firm' => 'F',
                    'create_client.type.team_project' => 'T',
                ],
                //'choices_as_values' => false,
                'expanded' => false,
                'multiple' => false,
                'placeholder' => false
            ])
        /*
        ->add('email', EmailType::class, [
            'constraints' => [
                //new Assert\NotBlank,
                new UniquePerOrganization([
                    'organization' => $organization,
                    'entity' => 'client',
                    'element' => $client,
                    'property' => 'email',
                    'message' => 'create_users.doublon_email_user'
                ]),
                new Assert\Regex([
                    'pattern' => "/^(?:[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])*$/",
                    'message' => '*Email is invalid, please enter a valid email adress in this field (example : some@thing.com).'
                ]),
            ],
            'label_format' => 'create_client.%name%',
            'required' => false,
        ])*/;

        if($options['hasChildrenElements'] != false){

            $builder->add('aliveExternalUsers', CollectionType::class,
                [
                    'entry_type' => ExternalUserType::class,
                    'entry_options' => [
                        //'organization' => $options['organization'],
                        'hasClientActiveAdmin' => $client && $client->getClientOrganization()->hasActiveAdmin(),
                        'standalone' => false,
                    ],
                    'constraints' => [
                        new AtLeastOneOwnerAtInception,
                    ],
                    'prototype'    => true,
                    'by_reference' => false,
                    'allow_delete' => 'true',
                    'allow_add' => 'true',
                    'label' => false,

                ]);
        }





        //TODO : afficher le total déjà distribué dans le label
        //TODO : créer une contrainte de non dépassement en cas de définition relatives

        if($options['standalone']){
            $builder->add('submit', SubmitType::class,[
                'label' => 'Soumettre',
                'attr' => [
                    'class' => 'btn-large waves-effect waves-light teal lighten-1 user-submit',
                ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {

        $resolver->setDefault('data_class',Client::class);
        $resolver->setDefault('organization', null);
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');
        $resolver->setDefault('usersLinked', true);
        $resolver->setDefault('hasChildrenElements', true);

    }

}
