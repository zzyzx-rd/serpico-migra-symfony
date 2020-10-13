<?php
namespace App\Form;

use App\Entity\Organization;
use App\Entity\Position;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Validator\UniquePerOrganization;

class AddSignupUserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       
        $builder
        ->add('firstname', TextType::class,
            [
                'label_format' => '%name%',
                'constraints' => new Assert\NotBlank,
                'required' => false,
            ]
        )
        ->add('lastname', TextType::class,
            [
                'label_format' => '%name%',
                'constraints' => new Assert\NotBlank,
                'required' => false,
            ]
        )
        ->add('email', TextType::class,
            [
                'label_format' => '%name%',
                'constraints' => [
                    new Assert\NotBlank,
                    new Assert\Regex([
                        'pattern' => "/^(?:[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])*$/",
                        'message' => '*Email is invalid, please enter a valid email adress in this field (example : some@thing.com).'
                    ]),
                    new UniquePerOrganization([
                        'organization' => null,
                        'entity' => 'user',
                        'element' => $builder->getData(),
                        'property' => 'email',
                        'message' => 'create_user.email'
                    ]),
                ],
                'required' => false,
            ]
        )
        ->add('submit', SubmitType::class,
            [
                'label' => 'create_organization.letz_go',
                'attr' => [
                    'class'=> 'btn btn-large'
                ]
            ]
        );
    
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class',User::class);
    }
}
