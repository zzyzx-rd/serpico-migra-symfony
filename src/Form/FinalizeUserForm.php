<?php

namespace App\Form;

use Controller\MasterController;
use App\Entity\Organization;
use App\Entity\User;
use App\Entity\Department;
use App\Entity\Position;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints as Assert;

class FinalizeUserForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {

      $user = $options['user'];

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
              'data' => $user->getFirstname(),
              'required' => true,
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
              'data' => $user->getLastname(),
              'required' => true,

          ])
          /*
          ->add('email', TextType::class, [
              'constraints' => [
                  new Assert\NotBlank,
                  new Assert\Regex([
                      'pattern' => "/^[a-zA-Z0-9_.\\-]+@[a-zA-Z_\\-]+?\.[a-zA-Z]{2,3}$/",
                      'message' => '*Email is invalid, please enter a valid email adress in this field (example : some@thing.com).'
                  ]),
              ],
              'data' => $user->getEmail(),
              'required' => true,

          ])*/
          ->add('department', TextType::class, [

              'constraints' => [
                  new Assert\NotBlank,
                  new Assert\Regex([
                      'pattern' => "/[a-zA-Z]/",
                      'message' => '*Lastname is invalid, please use only letters a-z, A-Z in this field.'
                  ])
              ],
              'label_format' => 'create_user.%name%',
              'data' => 'General',
              'required' => true,
          ])
          ->add('position', TextType::class, [

              'constraints' => [
                  new Assert\NotBlank,
                  new Assert\Regex([
                      'pattern' => "/[a-zA-Z]/",
                      'message' => '*Lastname is invalid, please use only letters a-z, A-Z in this field.'
                  ])
              ],
              'label_format' => 'create_user.%name%',
              'data' => $user->getPositionName(),
              'required' => true,
          ])->add('submit', SubmitType::class,[
              'label_format' => 'user_update.%name%',
              'attr' => [
                  'class' => 'btn-large waves-effect waves-light teal lighten-1 user-submit',
              ]
          ]);

  }

    public function configureOptions(OptionsResolver $resolver)
    {
        //$resolver->setDefault('data_class',User::class);
        $resolver->setRequired('user');

    }
}
