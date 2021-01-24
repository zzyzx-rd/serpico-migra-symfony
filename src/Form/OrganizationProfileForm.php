<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etudiant
 * Date: 07/12/2017
 * Time: 09:35
 */
namespace App\Form;

use App\Entity\Organization;
use App\Entity\WorkerIndividual;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\Type\WorkerExperienceType;
use Doctrine\Common\Collections\ArrayCollection;

class OrganizationProfileForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      

            $builder
            ->add('commname', TextType::class,
            [
                //'label_format' => "organization_profile.%name%",
                'required' => false,
                'label' => false,
            ])

            ->add('legalname', TextType::class,
            [
                //'label_format' => "organization_profile.%name%",
                'required' => false,
                'label' => false,
            ])

            ->add('plan', ChoiceType::class,
            [
                //'label_format' => "organization_profile.%name%",
                'label' => false,   
                'choices' => [
                    'subscription.free' => 3,
                    'subscription.premium' => 2
                ],
                'expanded' => true,
                'multiple' => false
            ])
            
            /*
            ->add('email', TextType::class,
            [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'no-margin'
                ],
                'mapped' => false,
                'data' => $currentUser->getEmail(),
            ])
            */

            /*

            ->add('url', TextType::class,
            [
                'label_format' => "worker_individual_data.%name%",
                'required' => false,
            ])
            */
            ;

            /*
            $builder->add('experiences', CollectionType::class, [
                    'entry_type' => WorkerExperienceType::class,
                    'prototype'    => true,
                    'by_reference' => false,
                    'allow_delete' => true,
                    'allow_add' => true,
                    'label' => false,
                ]);
            */

        if ($options['standalone']){
            $builder->add('submit', SubmitType::class,[
                'label_format' => 'save',
                'attr' =>
                    [
                        'class' => 'm-left waves-effect waves-light btn save-btn disabled-btn',
                        'style' => 'display:none'
                    ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        //$resolver->setDefault('data_class',WorkerIndividual::class);
        $resolver->setDefault('data_class',Organization::class);
        $resolver->setDefault('standalone', false);
        $resolver->setDefault('currentUser', null);
        $resolver->addAllowedTypes('standalone', 'bool');
    }

}
