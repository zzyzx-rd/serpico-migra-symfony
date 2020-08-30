<?php

namespace App\Form\Type;

use App\Entity\EventDocument;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;

class EventDocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('path', FileType::class,[
            //'label' => 'Upload multiple users (CSV file)',
            'required' => false,
            'attr' => [
                'class' => 'dropify',
                'data-allowed-file-extensions' => 'csv',
                'data-max-file-size' => '1M',
                'data-height' => '60'
            ],
            'invalid_message' => 'create_user.csv_sub_error',
        ])
        ->add('title', TextType::class,
        [
            'label' => false,
            'required' => true,
            'attr' => [
                'class' => 'no-margin',
            ],
        ]);


        if ($options['standalone']){
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
        $resolver->setDefault('data_class',EventDocument::class);
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');

    }

}
