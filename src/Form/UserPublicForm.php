<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etudiant
 * Date: 07/12/2017
 * Time: 09:35
 */
namespace App\Form;

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
use Symfony\Bridge\Doctrine\App\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use App\Entity\Title;

class UserPublicForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $organization = $options['organization'];

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

            ->add('selfUsername', TextType::class, [
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
                        'message' => 'create_users.doublon_email_user'
                    ]),
                    new Assert\Regex([
                        'pattern' => "/^(?:[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])*$/",
                        'message' => '*Email is invalid, please enter a valid email adress in this field (example : some@thing.com).'
                    ]),
                ],
                'label_format' => 'create_user.%name%',
                'required' => false,

            ])

            ->add('firm', TextType::class,
            [
                'label_format' => "worker_individual_data.experience.%name%",
            ])

            ->add('posId', EntityType::class,
                [
                    'class' => Position::class,
                    'choice_label' => 'name',
                    'query_builder' => function (EntityRepository $er) use ($organization) {

                        // Data is null when a new form is added, so we needed to find a way to add correct users

                        return $er->createQueryBuilder('p')
                        ->innerJoin('App\Entity\Organization', 'o', 'WITH', 'p.organization = o.id')
                        ->where("o.commname = 'Public'")
                        ->andWhere('p.deleted is NULL')
                        ->orderBy('p.name', 'ASC');

                    },
                    'attr' => [
                        'style' => 'font-family: Roboto, FontAwesome',
                    ],
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
                        ->innerJoin('App\Entity\Organization', 'o', 'WITH', 't.organization = o.id')
                        ->where("o.commname = 'Public'")
                        ->andWhere('t.deleted is NULL')
                        ->orderBy('t.name', 'ASC');

                    },
                    'attr' => [
                        'style' => 'display: block!important; font-family: Roboto, FontAwesome',
                    ],
                    'placeholder' => 'N/A',
                    'label_format' => 'create_user.%name%',
                    'required' => false,
                ]);

        //TODO : afficher le total déjà distribué dans le label
        //TODO : créer une contrainte de non dépassement en cas de définition relatives

        if ($options['standalone']){
            $builder->add('submit', SubmitType::class,[
                'label_format' => 'user_update.%name%',
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
        $resolver->addAllowedTypes('standalone', 'bool');
    }
}
