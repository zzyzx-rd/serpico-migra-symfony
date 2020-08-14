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

class PasswordDefinitionForm extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options){
      $builder->add(
                'password',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'required' => true,
                    'first_options' => [
                        'label_format' => 'password_definition.password',
                    ],
                    'second_options' => [
                        'label_format' => 'password_definition.password_repeat'
                    ],
                    'invalid_message' => 'password_definition.unmatching_msg',
                    /*'options' => [
                        'attr' => [
                            'class' => 'password-field'
                        ]
                    ],*/
                    'constraints' => [
                        new Assert\NotBlank,
                        new Assert\Regex([
                                'value' => '/^(?=.*\d+)(?=.*[A-Z]+)(.{8,})$/',
                                'message' => 'password_definition.password',
                        ])
                    ]
                ]
            );

        if ($options['standalone']) {
            $builder->add('submit', SubmitType::class,[
                'label_format' => 'password_definition.%name%',
                'attr' => [
                    'class' => 'waves-effect waves-light btn-large',
                    /*'disabled' => 'disabled'*/
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
