<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etudiant
 * Date: 07/12/2017
 * Time: 09:35
 */
namespace App\Form;

use App\Entity\City;
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
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TelType;

class OrganizationProfileForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
            /** @var Organization */
            $org = $builder->getData();

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
            
            ->add('location', TextType::class,
            [
                'mapped' => false,
            ])
            ->add('city', HiddenType::class,
            [
                'mapped' => false,
            ])
            ->add('state', HiddenType::class,
            [
                'mapped' => false,
            ])
            ->add('ZIPCode', HiddenType::class,
            [
                'mapped' => false,
            ])
            ->add('country', HiddenType::class,
            [
                'mapped' => false,
            ])
            
            ->add('contactName', TextType::class,
            [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'no-margin'
                ],
                'data' => $org->getContactName() ?: $org->getSuperAdmin()->getFullname(),
            ])


            ->add('contactPhoneNumber', TelType::class,
            [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'no-margin'
                ],
            ])
            

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
