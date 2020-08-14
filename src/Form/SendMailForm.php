<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etudiant
 * Date: 07/12/2017
 * Time: 09:35
 */
namespace App\Form;

use App\Form\Type\MailParameterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;

class SendMailForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('emailType', ChoiceType::class,[
            'choices' =>
                [
                    'Registration' => 'registration',
                    'Request' => 'request',
                    'Delegate' => 'delegate',
                    'Subscription' => 'subscription',
                ],
            //'choices_as_values' => true,
            /*'choice_label' => function ($value, $key, $index) {
                if ($key == 1) {
                    return 'create_user.administrator';
                } elseif ($key == 2) {
                    return 'create_user.activity_manager';
                } elseif ($key == 3) {
                    return 'create_user.collaborator';
                }
            },
            'label_format' => 'create_user.%name%',*/
            'expanded' => false,
            'multiple' => false,
            'attr' => [
                'style' => 'display: block!important',
            ],
            'data' => 'Registration',
            'label' => 'Mail type',
            /*
            'attr' => [
                'class' => 'user-role',
            ]*/
        ])
            ->add('emailParameters', CollectionType::class, [
                'entry_type' => MailParameterType::class,
                'prototype'    => true,
                'by_reference' => false,
                'allow_delete' => 'true',
                'allow_add' => 'true'

            ])
            ->add('lang', ChoiceType::class,[
                'choices' =>
                    [
                        'fr' => 'FR',
                        'en' => 'EN',
                        'pt' => 'PT',
                    ],
                'expanded' => true,
                'multiple' => false,
                'data' => 'FR',
                'label' => 'Language',
            ])
            ->add('emailAddress', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank,
                    /*new Assert\Regex([
                        'pattern' => "/^[a-zA-Z0-9_.]+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/",
                        'message' => '*Email is invalid, please enter a valid email adress in this field (example : some@thing.com).'
                    ]),*/
                ],
                'label' => 'Email address',
                'required' => true,
            ])
        ;


        if ($options['standalone']){
            $builder->add('submit', SubmitType::class,[
                'label_format' => 'Test mail',
                'attr' =>
                    [
                        'class' => 'waves-effect waves-light btn-large blue darken-4',
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
