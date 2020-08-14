<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etudiant
 * Date: 07/12/2017
 * Time: 09:35
 */
namespace App\Form;

use App\Entity\Organization;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\Type\ClientUserMType;
use App\Form\Type\UserMType;
use App\Form\Type\ActivityNameType;

class UpdateOrganizationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


            $app = $options['app'];
            $em = $app['orm.em'];
            $repoU = $em->getRepository(User::class);
            //Get users, build choices array
            $users = $repoU->findByOrgId($options['organization']->getId());
            $keys = [];
            $values = [];
            foreach($users as $user){
                $keys[] = $user->getId();
                $values[] = $user->getFirstname().' '.$user->getLastname();
                //$choices['value'][] = $user->getFirstname().' '.$user->getLastname();
            }


            $builder->add('commName', TextType::class,
            [
                'label_format' => "massive_update_organization.%name%",
                'constraints' => [
                    new Assert\NotBlank
                ],
            ])

            ->add('type', ChoiceType::class, [
                'choices' => [
                    'create_organization.type.institution' => 'P',
                    'create_organization.type.client' => 'F',
                    'create_organization.type.team_project' => 'T',
                    'create_organization.type.individual' => 'I',
                ],
                'expanded' => false,
                'multiple' => false,
                'attr' => [
                    'style' => 'display: block!important',
                ],
                'constraints' => [
                    new Assert\NotBlank,
                ],
                'label' => 'create_organization.type.label',
                'required' => true,
                'placeholder' => false,
            ])

            ->add('masterUserId', ChoiceType::class,
                [
                    'label_format' => 'massive_update_organization.%name%',
                    'choices' => array_combine($values,$keys),
                    'expanded' => false,
                    'multiple' => false,
                    'constraints' => [
                        new Assert\NotBlank
                    ],
                    'attr' => [
                        'style' => 'margin-bottom: 15px; display: block!important',
                    ]
                ])

            ->add('expired', DateTimeType::class,
                [
                    'format' => 'dd/MM/yyyy',
                    'widget' => 'single_text',
                    'label_format' => 'massive_update_organization.%name%',
                    'html5' => false,
                    'attr' => ['class' => 'dp-end'],
                    'data' => ($options['organization']->getExpired() != null) ? $options['organization']->getExpired() : new \DateTime("1 January 2100"),
                    'constraints' => [
                        new Assert\NotBlank,
                        //new EDGreaterThanSD
                    ]
                ])

                ->add('orgUsers', CollectionType::class,
            [
                'entry_type' => UserMType::class,
                'entry_options' => [
                    'user' => null,
                    'app' => $options['app'],
                    'organization' => $options['organization'],
                ],
                'prototype' => true,
                'by_reference' => false,
                'allow_delete' => 'true',
                'allow_add' => 'true',
                //'label' => 'Insert new users (already '.$options['nbweights'].' voting powers attributed totalling '.round($options['totalweights'],1). ' weights)'

            ])

            ->add('externalUsers', CollectionType::class,
            [
                'entry_type' => ClientUserMType::class,
                'prototype' => true,
                'by_reference' => false,
                'allow_delete' => 'true',
                'allow_add' => 'true',
                //'label' => 'Insert new users (already '.$options['nbweights'].' voting powers attributed totalling '.round($options['totalweights'],1). ' weights)'

            ])

            ->add('activities', CollectionType::class,
            [
                'entry_type' => ActivityNameType::class,
                'by_reference' => false,
                'allow_delete' => 'true',
                'allow_add' => 'false',
            ])
            ;


        if ($options['standalone']){
            $builder->add('submit', SubmitType::class,[
                'label' => "Sauvegarder les modifications",
                //'label_format' => 'create_user.%name%',
                'attr' =>
                    [
                        'class' => 'waves-effect waves-light btn-large blue darken-4 create-users',
                    ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class',Organization::class);
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');
        $resolver->setDefault('organization', null);
        $resolver->setDefault('app', null);
        //$resolver->setRequired('totalweights');
        //$resolver->setRequired('nbweights');

    }

}
