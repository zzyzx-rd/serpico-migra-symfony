<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etudiant
 * Date: 07/12/2017
 * Time: 09:35
 */
namespace App\Form\Type;

use App\Entity\Department;
use App\Entity\Position;
use App\Entity\User;
use App\Entity\Weight;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints as Assert;
use Validator\UniquePerOrganization;
use Controller\MasterController;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use App\Entity\Title;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $organization = $options['organization'];
        $user = $builder->getData();

        $builder->add('firstname', TextType::class,
            [
                'constraints' => [
                    new Assert\NotBlank,
                    new Assert\Regex([
                        'pattern' => "/[a-zA-Z]/",
                        'message' => 'Firstname is invalid, please use only letters a-z, A-Z in this field.'
                    ])
                ],
                'label_format' => 'create_user.%name%',
                'required' => false,
            ])
            ->add('lastname', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank,
                    new Assert\Regex([
                        'pattern' => "/[a-zA-Z]/",
                        'message' => '*Lastname is invalid, please use only letters a-z, A-Z in this field.'
                    ])
                ],
                'label_format' => 'create_user.%name%',
                'required' => false,

            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Assert\NotBlank,
                    new UniquePerOrganization([
                        'organization' => $organization,
                        'entity' => 'user',
                        'element' => $builder->getData(),
                        'property' => 'email',
                        'message' => 'create_users.doublon_email_user',
                    ]),
                    new Assert\Regex([
                        'pattern' => "/^(?:[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])*$/",
                        'message' => '*Email is invalid, please enter a valid email adress in this field (example : some@thing.com).'
                    ]),
                ],
                'label_format' => 'create_user.%name%',
                'required' => false,

            ])
            ->add('role', ChoiceType::class,
                [
                    'choices' =>
                        [
                            1 => 1,
                            2 => 2,
                            3 => 3,
                        ],
                    //'choices_as_values' => true,
                    'choice_label' => function ($value, $key, $index) {
                        if ($value == 1) {
                            return 'create_user.administrator';
                        } elseif ($value == 2) {
                            return 'create_user.contributor';
                        } elseif ($value == 3) {
                            return 'create_user.collaborator';
                        }
                    },
                    'label_format' => 'create_user.%name%',
                    'expanded' => false,
                    'multiple' => false,
                ])

            ->add('dptId', EntityType::class,
                [
                    'class' => Department::class,
                    'choice_label' => 'name',
                    'query_builder' => function (EntityRepository $er) use ($organization) {

                        // Data is null when a new form is added, so we needed to find a way to add correct users

                        return $er->createQueryBuilder('d')
                        ->where('d.organization ='. $organization)
                        ->andWhere('d.deleted is NULL')
                        ->orderBy('d.name', 'ASC');

                    },
                    'data' => ($user != null) ? $user->getDepartment() : null,
                    'placeholder' => 'N/A',
                    'label_format' => 'create_user.%name%',
                    'required' => false,
                ])

            /*
            ->add('department', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank,
                    new Assert\Regex([
                        'pattern' => "/[a-zA-Z0-9]/",
                        'message' => '*The department field is currently invalid, please do not use special characters.'
                    ])
                ],
                'label' => 'Department',
                'data' => ($user != null) ? $department->getName() : null,
                'required' => true,
            ])*/

            ->add('posId', EntityType::class,
                [
                    'class' => Position::class,
                    'choice_label' => 'name',
                    'query_builder' => function (EntityRepository $er) use ($organization) {

                        // Data is null when a new form is added, so we needed to find a way to add correct users

                        return $er->createQueryBuilder('p')
                        ->where('p.organization ='. $organization)
                        ->andWhere('p.deleted is NULL')
                        ->orderBy('p.name', 'ASC');

                    },
                    'data' => ($user != null) ? $user->getPosition() : null,
                    'placeholder' => 'N/A',
                    'label_format' => 'create_user.%name%',
                    'required' => false,
                ])

                ->add('titId', EntityType::class,
                [
                    'class' => Title::class,
                    'choice_label' => 'name',
                    'query_builder' => function (EntityRepository $er) use ($organization) {

                        // Data is null when a new form is added, so we needed to find a way to add correct users

                        return $er->createQueryBuilder('t')
                        ->where('t.organization ='. $organization)
                        ->andWhere('t.deleted is NULL')
                        ->orderBy('t.name', 'ASC');

                    },
                    'data' => ($user != null) ? $user->getTitle() : null,
                    'placeholder' => 'N/A',
                    'label_format' => 'create_user.%name%',
                    'required' => false,
                ])

                ->add('wgtId', EntityType::class,
                [
                    'class' => Weight::class,
                    'choice_label' => 'value',
                    'query_builder' => function (EntityRepository $er) use ($organization) {

                        // Data is null when a new form is added, so we needed to find a way to add correct users

                        return $er->createQueryBuilder('w')
                        ->where('w.organization ='. $organization)
                        ->andWhere('w.deleted is NULL')
                        ->orderBy('w.value', 'ASC');

                    },
                    'label_format' => 'create_user.influence',
                    'required' => false,
                    'placeholder' => false,
                    'constraints' => [
                        new Assert\NotNull,
                    ],
                ])


            /*
            ->add('weightIni', NumberType::class, [
                'constraints' => [
                    new Assert\NotBlank,
                ],
                'data' => ($user != null) ? $user->getWeightIni() : null,
                'label_format' => 'create_user.%name%',
            ])
            
            ->add('internal', CheckboxType::class, [
                'label_format' => 'create_user.%name%',
                'required' => true,
                'attr' => [
                    'class' => 'filled-in'
                ]
            ])
            */

            //if($user != null){

            ->add('superiorUser', EntityType::class,
            [
                'class' => User::class,
                'choice_label' => 'invertedFullname',
                'query_builder' => function (EntityRepository $er) use ($user, $organization) {

                    // Data is null when a new form is added, so we needed to find a way to add correct users
                    if($user != null){
                        return $er->createQueryBuilder('u')
                        ->where('u.orgId ='. $user->getOrganization()->getId())
                        ->andWhere("u.lastname != 'ZZ'")
                        ->andWhere("u.id != ". $user->getId())
                        ->andWhere('u.deleted is NULL')
                        ->orderBy('u.lastname', 'ASC');

                    } else {
                        return $er->createQueryBuilder('u')
                        ->where('u.orgId ='. $organization->getId())
                        ->andWhere("u.lastname != 'ZZ'")
                        ->andWhere('u.deleted is NULL')
                        ->orderBy('u.lastname', 'ASC');
                    }

                },
                'label_format' => 'create_user.%name%',
                'placeholder' => "user_list.define_superior.undefined",
                'required' => false,
            ]);

                /*
                $builder->add('superior', EntityType::class,
                [
                    'class' => User::class,
                    'choice_label' => 'fullName',
                    'query_builder' => function (EntityRepository $er) use ($organization, $user) {

                        // Data is null when a new form is added, so we needed to find a way to add correct users

                        return $er->createQueryBuilder('u')
                        ->where('u.orgId ='. $organization)
                        ->andWhere('u.id != '. $user->getId())
                        ->andWhere("u.lastname != 'ZZ'")
                        ->andWhere('u.deleted is NULL')
                        ->orderBy('u.lastname', 'ASC');

                    },
                    'attr' => [
                        'style' => 'display: block!important; font-family: Roboto, FontAwesome',
                    ],
                    'label_format' => 'create_user.%name%',
                    'placeholder' => 'user_list.define_superior.undefined',
                    'required' => false,
                ]);
                */
            //};

            if($options['enabledCreatingUser']){
                $builder->add('enabledCreatingUser', CheckboxType::class, [
                    'label_format' => 'create_user.%name%',
                    'required' => false,
                    'attr' => [
                        'class' => 'filled-in'
                    ]
                    ]);
            }


        //TODO : afficher le total déjà distribué dans le label
        //TODO : créer une contrainte de non dépassement en cas de définition relatives

        if ($options['standalone']){
            $builder->add('submit', SubmitType::class,[
                'label_format' => 'update',
                'attr' => [
                    'class' => 'btn-large waves-effect waves-light teal lighten-1 user-submit',
                ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', User::class);
        $resolver->setDefault('organization',false);
        $resolver->setDefault('standalone', false);
        $resolver->setDefault('enabledCreatingUser', false);
        $resolver->addAllowedTypes('standalone', 'bool');
    }
}
