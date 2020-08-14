<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etudiant
 * Date: 07/12/2017
 * Time: 09:35
 */
namespace App\Form;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;
use Validator\UniquePerOrganization;


class SignUpForm extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options){

      $builder
            ->add('username', TextType::class,
            [
                'label_format' => 'signup.%name%',
                'constraints' => [
                    new Assert\NotBlank
                ],
            ])
            ->add('nickname', TextType::class,
            [
                'label_format' => 'signup.%name%',

            ])
            ->add('firstname', TextType::class,
            [
                'label_format' => 'signup.%name%',
                'required'   => false,
            ])
            ->add('lastname', TextType::class,
            [
                'label_format' => 'signup.%name%',
                'required'   => false,
            ])

            ->add('email', TextType::class,
            [
                'label_format' => 'signup.%name%',
                'constraints' => [
                    new Assert\NotBlank,
                    new Assert\Regex([
                        'pattern' => "/^(?:[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])*$/",
                        'message' => 'signup.email',
                    ]),
                    new UniquePerOrganization([
                        'entity' => 'user',
                        'element' => $builder->getData(),
                        'property' => 'email',
                        'message' => 'create_users.doublon_email_user'
                    ]),
                ],
            ])

            ->add('password', PasswordType::class,
            [
                'label_format' => 'signup.%name%',
                'constraints' => [
                    new Assert\NotBlank,
                    new Assert\Regex([
                            'value' => '/^(?=.*\d+)(?=.*[A-Z]+)(.{8,})$/',
                            'message' => 'signup.password',
                    ])
                ],
            ]
            );

        if ($options['standalone']) {
            $builder->add('submit', SubmitType::class,[
                'label_format' => 'signup.%name%',
                'attr' => [
                    'class' => 'waves-effect waves-light btn-large',
                    /*'disabled' => 'disabled'*/
                ]
            ]);
        }
    }
    public function configureOptions(OptionsResolver $resolver)
      {
          $resolver->setDefault('data_class',User::class);
          $resolver->setDefault('standalone', false);
          $resolver->addAllowedTypes('standalone', 'bool');
      }
}
