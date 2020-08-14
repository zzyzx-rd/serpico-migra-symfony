<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;

class AddFirstAdminForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {

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

              'required' => true,
          ])
          ->add('lastname', TextType::class, [
              'constraints' => [
                  new Assert\NotBlank,
                  new Assert\Regex([
                      'pattern' => "/[a-zA-Z]/",
                      'message' => 'Lastname is invalid, please use only letters a-z, A-Z in this field.'
                  ])
              ],
              'label_format' => 'create_user.%name%',
              'required' => true,

          ])

          ->add('email', TextType::class, [
              'constraints' => [
                  new Assert\NotBlank,
                  new Assert\Regex([
                      'pattern' => "/^(?:[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])*$/",
                      'message' => 'Email is invalid, please enter a valid email adress in this field (example : some@thing.com).'
                  ]),
              ],

          ])

          ->add('submit', SubmitType::class,[
            'label_format' => 'user_update.%name%',
            'attr' => [
                'class' => 'btn waves-effect waves-light teal lighten-1 admin-submit',
            ]
            ]);
        }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class',User::class);
    }
}
