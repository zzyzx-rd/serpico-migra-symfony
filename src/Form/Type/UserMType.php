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
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints as Assert;
use Controller\MasterController;
use Doctrine\ORM\EntityRepository;
use App\Entity\Title;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class UserMType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];
        $app = $options['app'];
        $organization = $options['organization'];
        $keys = [];
        $values = [];
        $keysPos = [];
        $keysWgt = [];
        $valuesPos = [];
        $valuesWgt = [];

        $keys[] = "N/A";
        $values[] = 0;

        $keysPos[] = "N/A";
        $valuesPos[] = 0;

        if($organization){
            $departments = $organization->getDepartments();
            foreach ($departments as $department){
                $keys[] = $department->getName();
                $values[] = $department->getId();
            }
            $positions = $organization->getPositions();
            foreach($positions as $position){
                $keysPos[] = $position->getName();
                $valuesPos[] = $position->getId();
            }

            $weights = $organization->getWeights();
            foreach($weights as $weight){
                $keysWgt[] = ($weight->getPosition() !== null) ? $weight->getValue().' ('.$weight->getPosition()->getName().')' : $weight->getValue();
                $valuesWgt[] = $weight->getId();
            }
        }

        //print_r(array_combine($valuesPos,$keysPos   ));


        // Get data (multi entity Form)
        if($user != null){
            $repoD = $app['orm.em']->getRepository(Department::class);
            $repoP = $app['orm.em']->getRepository(Position::class);
            $repoW = $app['orm.em']->getRepository(Weight::class);
            $department = $repoD->findOneById($options['user']->getDepartment($app));
            $position = $repoP->findOneById($options['user']->getPosition($app));
            $weight = $repoW->findOneById($user->getWgtId());
        }

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
            ->add('email', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank,
                    new Assert\Regex([
                        'pattern' => "/^(?:[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])*$/",
                        'message' => '*Email is invalid, please enter a valid email adress in this field (example : some@thing.com).'
                    ]),
                ],
                'required' => false,
            ])
            ->add('password', TextType::class, [
                'constraints' => [
                    new Assert\Regex([
                        'value' => '/^(?=.*\d+)(?=.*[A-Z]+)(.{8,})$/',
                        'message' => 'signup.password',
                    ]),
                ],
                'data' => null,
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
                            return 'create_user.activity_manager';
                        } elseif ($value == 3) {
                            return 'create_user.collaborator';
                        }
                    },
                    'label_format' => 'create_user.%name%',
                    'expanded' => true,
                    'multiple' => false,
                    'attr' => [
                            'class' => 'user-role',
                    ],
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
                    'attr' => [
                        'style' => 'display: block!important; font-family: Roboto, FontAwesome',
                    ],
                    'data' => ($user != null) ? $user->getDepartment() : null,
                    'placeholder' => 'N/A',
                    'label_format' => 'create_user.%name%',
                    'required' => false,
                ])

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
                    'attr' => [
                        'style' => 'display: block!important; font-family: Roboto, FontAwesome',
                    ],
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
                    'attr' => [
                        'style' => 'display: block!important; font-family: Roboto, FontAwesome',
                    ],
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
                    'attr' => [
                        'style' => 'display: block!important; font-family: Roboto, FontAwesome',
                    ],
                    'data' => ($user != null) ? $user->getWeight() : null,
                    'placeholder' => 'N/A',
                    'label_format' => 'create_user.%name%',
                    'required' => false,
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
            */
            ->add('internal', CheckboxType::class, [
            'data' => ($user != null) ? $user->isInternal() : true,
            'label_format' => 'create_user.%name%',
            'required' => true,
            'attr' => [
                'class' => 'filled-in'
            ]
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
        $resolver->setDefault('data_class',User::class);
        $resolver->setRequired('app');
        $resolver->setRequired('user');
        $resolver->setRequired('organization');
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');

    }

}
