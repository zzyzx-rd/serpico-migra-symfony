<?php

namespace App\Form\Type;

use App\Entity\EventDocument;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File;

class EventDocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('title', TextType::class,
        [
            'label_format' => 'activity_elements.events.documents.document.%name%',
            'required' => true,
            'attr' => [
                'class' => 'no-margin input-field',
            ],
        ])
        ->add('file', FileType::class,[
            //'label' => 'Upload multiple users (CSV file)',
            'label' => false,
            'required' => false,
            'attr' => [
                'class' => 'dropify',
                //'data-allowed-file-extensions' => 'csv',
                'data-max-file-size' => '5M',
                'data-height' => '60'
            ],
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        //'application/pdf',
                        //'application/x-pdf',
                    ],
                    'mimeTypesMessage' => 'Please upload a valid PDF document'
                ])
            ],
            'invalid_message' => 'create_user.csv_sub_error',
        ])
      

        ->add('documentAuthors', CollectionType::class,
        [
            'label' => false,
            'entry_type' => DocumentAuthorType::class,
            'prototype' => true,
            'by_reference' => false,
            'allow_delete' => true,
            'allow_add' => true,
            'entry_options' => [
                'currentUser' => $options['currentUser'],
            ]
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
        $resolver->setDefault('currentUser', null);

    }

}
