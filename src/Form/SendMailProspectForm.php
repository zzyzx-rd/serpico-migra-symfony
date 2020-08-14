<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etudiant
 * Date: 07/12/2017
 * Time: 09:35
 */
namespace App\Form;

use App\Entity\WorkerIndividual;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;

class SendMailProspectForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('pType', ChoiceType::class,
        [
            'choices' => [
                'CEO' => 1,
                'Project Mgr' => 2,
                'Simple Mgr' => 3,
                'HR' => 4,
                'Worker' => 5,
            ],
            'expanded' => false,
            'multiple' => false,
            'attr' => [
                'style' => 'display: block!important',
            ],
            'constraints' => [
                new Assert\NotBlank,
            ],
            'label_format' => "worker_individual_data.%name%",
            'required' => true,
            'placeholder' => false,
        ])

        ->add('language', ChoiceType::class,
        [
            'choices' => [
                'FR' => 1,
                'EN' => 2,
                'FR-EN' => 12,
                'EN-FR' => 21,
            ],
            'expanded' => false,
            'multiple' => false,
            'attr' => [
                'style' => 'display: block!important',
            ],
            'constraints' => [
                new Assert\NotBlank,
            ],
            'label_format' => "worker_individual_data.%name%",
            'required' => true,
            'placeholder' => false,
        ])

        ->add('addPresentationFR', CheckboxType::class,
        [
            'label' => 'Add presentation as embedded doc (FR)',
            'attr' => [
                'class' => 'filled-in'
            ]
        ])

        ->add('addPresentationEN', CheckboxType::class,
        [
            'label' => 'Add presentation as embedded doc (EN)',
            'attr' => [
                'class' => 'filled-in'
            ]
        ])
        ;


        if ($options['standalone']){
            $builder->add('submit', SubmitType::class,[
                'label' => 'Soumettre',
                'attr' => [
                    'class' => 'btn-large waves-effect waves-light teal lighten-1 mail-submit',
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
