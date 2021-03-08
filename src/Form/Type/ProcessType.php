<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 28/12/2017
 * Time: 15:25
 */

namespace App\Form\Type;

use App\Entity\Process;
use App\Entity\InstitutionProcess;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
use PackageVersions\FallbackVersions;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class ProcessType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if($options['level'] <= $options['limitLevel']){

            $level = $options['level'] + 1;

            $organization = $options['organization'];

            $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($builder, $organization,$options, $level) {

                $data = $event->getData();
                $form = $event->getForm();

                if(!$options['isRoot']){

                    $form->add('process', EntityType::class,
                    [
                        'class' => Process::class,
                        'choice_label' => 'name',
                        'query_builder' => function (EntityRepository $er) use ($data) {
                                return $er->createQueryBuilder('p')
                                //->where('p.parent is NULL')
                                ->andWhere('p.deleted is NULL')
                                ->orderBy('p.name', 'ASC');
                        },
                        'constraints' => [
                            new Assert\NotBlank,
                        ],
                        'label_format' => 'processes.%name%',
                        'attr' => [
                            'class' => 'family-select',
                        ],
                        'required' => false,
                    ])
                    ->add('masterUser', EntityType::class,
                    [
                        'class' => User::class,
                        'choice_label' => 'fullname',
                        'query_builder' => function (EntityRepository $er) use ($data, $organization) {

                            // Data is null when a new form is added, so we needed to find a way to add correct users
                            if($data != null){
                                return $er->createQueryBuilder('u')
                                ->where('u.organization ='. $data->getOrganization())
                                ->andWhere('u.deleted is NULL')
                                ->andWhere("u.lastname != 'ZZ'")
                                ->orderBy('u.lastname', 'ASC');

                            } else {
                                $qb = $er->createQueryBuilder('u');
                                return $qb
                                ->where('u.organization ='. $organization)
                                ->andWhere('u.deleted is NULL')
                                ->andWhere("u.lastname != 'ZZ'")
                                ->orderBy('u.lastname', 'ASC');
                            }

                        },
                        'label_format' => 'processes.%name%',
                        'required' => false,
                    ]);

                }

                $form->add('gradable', CheckboxType::class, [
                    'label_format' => 'processes.%name%',
                    'required' => true,
                    'attr' => [
                        'class' => 'filled-in'
                    ]
                ])
                ->add('parent', EntityType::class,
                [

                    'class' => $options['isRoot'] ? Process::class : InstitutionProcess::class,
                    'choice_label' => 'name',
                    'query_builder' => function (EntityRepository $er) use ($data, $organization,$options) {

                        $qb = $er->createQueryBuilder('p');

                        if($options['isRoot']){
                                return $qb
                                ->where('p.deleted is NULL')
                                ->orderBy('p.name', 'ASC');
                        } else {
                            // Data is null when a new form is added, so we needed to find a way to add correct users
                            if($data != null){
                                return $qb
                                ->where('p.organization ='. $data->getOrganization())
                                ->andWhere('p.deleted is NULL')
                                ->orderBy('p.name', 'ASC');

                            } else {
                                return $qb
                                ->where('p.organization ='. $organization->getId())
                                ->andWhere('p.deleted is NULL')
                                ->orderBy('p.name', 'ASC');
                            }
                        }
                    },
                    'label_format' => 'processes.%name%',
                    'required' => false,
                    'attr' => [
                        'class' => 'parent-select',
                    ]
                ])

                ->add('validatedChildren', CollectionType::class,
                [
                    'entry_type' => ProcessType::class,
                    'entry_options' => [
                        'organization' => $options['organization'],
                        'isRoot' => $options['isRoot'],
                        'standalone' => true,
                        'level' => $level,
                        'limitLevel' => 2,
                        'label' => false,
                    ],
                    'prototype'    => true,
                    'by_reference' => false,
                    'allow_delete' => 'true',
                    'allow_add' => 'true',
                    'label' => false,

                ]);



            });

            $builder->add('name', TextType::class,
                [
                    'constraints' => [
                        new Assert\NotBlank([
                            //'message' => 'The stage must have a name'
                        ]),
                    ],
                    'label_format' => 'processes.%name%',
            ]);

        }

    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('isRoot', false);
        $resolver->setDefault('data_class',function (Options $options){
            if(!$options['isRoot']){
                return InstitutionProcess::class;
            } else {
                return Process::class;
            };
        });
        $resolver->setDefault('standalone', false);
        $resolver->setDefault('level', 0);
        $resolver->setDefault('limitLevel', 2);
        $resolver->setDefault('organization', null);
        $resolver->addAllowedTypes('standalone', 'bool');

    }
}
