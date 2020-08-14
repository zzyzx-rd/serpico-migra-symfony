<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etudiant
 * Date: 07/12/2017
 * Time: 09:35
 */
namespace App\Form\Type;

use Doctrine\ORM\EntityRepository;
use App\Entity\Organization;
use App\Entity\User;
use App\Entity\ExternalUser;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints as Assert;

class ExternalUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $hasClientActiveAdmin = $options['hasClientActiveAdmin'];

        $builder->add('firstname', TextType::class,
            [
                'label_format' => 'create_client.%name%',
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank,
                ],
                //'disabled' => ($user != null && $client->isClient()) ? true : false,
            ])
            ->add('lastname', TextType::class,
                [
                    'label_format' => 'create_client.%name%',
                    'required' => false,
                    'constraints' => [
                        new Assert\NotBlank,
                    ],
                    //'disabled' => ($user != null && $client->isClient()) ? true : false,
                ])

            ->add('email', TextType::class, [
                'label_format' => 'create_client.%name%',
                'required' => false,
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => "/^(?:[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])*$/",
                        'message' => '*Email is invalid, please enter a valid email adress in this field (example : some@thing.com).'
                    ]),
                ],
                'attr' => [
                    'class' => 'no-margin',
                ],
                //'data' => ($user != null) ? $user->getEmail() : null,
            ])
            ->add('positionName', TextType::class, [
                'label_format' => 'create_client.%name%',
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank,
                ],
                //'data' => ($user != null) ? $extUser->getPositionName() : null,
            ])
            ->add('weightValue', NumberType::class, [
                'attr' => [
                    'class' => 'no-margin',
                ],
                //'data' => ($externalUser != null) ? $externalUser->getWeight() : null,
                'label_format' => 'create_client.%name%',
                'required' => false,
                //'data' => ($user != null) ? $extUser->getWeightValue() : null,
            ]);

            if(!$hasClientActiveAdmin){

                $builder->add('owner', CheckboxType::class, [
                    'attr' => [
                        'class' => 'filled-in'
                    ],
                    'label_format' => 'create_client.%name%',
                    'required' => false,
                    //'data' => ($user != null) ? $extUser->getWeightValue() : null,
                ]);
            }

            /*
            if(!$options['usersLinked']){
                $builder->add('orgId', EntityType::class,
                [
                    'class' => Organization::class,
                    'choice_label' => 'commname',
                    'query_builder' => function (EntityRepository $er) use ($organization) {

                        // Data is null when a new form is added, so we needed to find a way to add correct users

                        return $er->createQueryBuilder('o')
                        ->innerJoin('App\Entity\Client', 'c', 'WITH', 'o = c.clientOrganization')
                        ->where('c.organization ='. $organization)
                        ->andWhere('o.deleted is NULL')
                        ->orderBy('o.commname', 'ASC');

                    },
                    'attr' => [
                        'style' => 'display: block!important; font-family: Roboto, FontAwesome',
                    ],
                    //'data' => ($user != null) ? $user->getPosition() : null,
                    'placeholder' => 'N/A',
                    'label_format' => 'create_user.%name%',
                    'required' => false,
                ]);
            }
            */




        //TODO : afficher le total déjà distribué dans le label
        //TODO : créer une contrainte de non dépassement en cas de définition relatives

        if($options['standalone']){
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
        $resolver->setDefault('data_class',ExternalUser::class);
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');
        $resolver->setDefault('hasClientActiveAdmin', true);
    }

}
