<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etudiant
 * Date: 07/12/2017
 * Time: 09:35
 */
namespace App\Form;
use App\Entity\User;
use App\Validator\PasswordControl;
use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;

class PasswordForm extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options){

        $currentUser = $builder->getData();

        $builder->add('password', PasswordType::class,
                [
                    'label_format' => 'profile.manage.password_modification_modal.old_password',
                    'required' => true,
                    'constraints' => [
                        new Assert\NotBlank,
                        new PasswordControl(
                            ['user' => $currentUser]
                        )
                    ],
                    'mapped' => false
                ]
        );

        if($options['mode'] == 'modification'){

            $builder->add('newPassword',PasswordType::class,
                [
                    'label_format' => 'profile.manage.password_modification_modal.new_password',
                    'required' => true,
                    'constraints' => [
                        new Assert\NotBlank,
                        new Assert\Regex([
                                'value' => '/^(?=.*\d+)(?=.*[A-Z]+)(.{8,})$/',
                                'message' => 'password.definition_rule',
                        ])
                    ],
                    'mapped' => false
                ]
            );
        }


        if($options['standalone']) {
            $builder->add('submit', SubmitType::class,[
                'label_format' => 'password_definition.%name%',
                'attr' => [
                    'class' => 'waves-effect waves-light btn pwd-modify-btn',
                    /*'disabled' => 'disabled'*/
                ]
            ]);
        }
    }
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefault('data_class',User::class);
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');
        $resolver->setDefault('mode', null);
    }
}
