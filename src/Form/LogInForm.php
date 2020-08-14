<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Description of LogInForm
 *
 * @author Etudiant
 */
class LogInForm extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder->add('email', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank,
                    new Assert\Regex([
                        'pattern' => "/^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/"
                    ])
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password',
                'required' => true


            ]);

        if ($options['standalone']){
            $builder->add('submit', SubmitType::class,[
                'label' => 'LOG IN'
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class',User::class);
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');
    }
}
