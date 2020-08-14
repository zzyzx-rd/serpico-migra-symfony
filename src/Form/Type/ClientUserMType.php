<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etudiant
 * Date: 07/12/2017
 * Time: 09:35
 */
namespace App\Form\Type;

use App\Entity\Organization;
use App\Entity\User;
use App\Entity\ExternalUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints as Assert;

class ClientUserMType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('firstname', TextType::class,
            [
                'label_format' => 'create_client.%name%',
                'required' => false,
            ])
            ->add('lastname', TextType::class,
                [
                    'label_format' => 'create_client.%name%',
                    'required' => false,
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
            ])
            ->add('positionName', TextType::class, [
                'label_format' => 'create_client.%name%',
                'required' => false,
            ])
            ->add('weightValue', NumberType::class, [
                'attr' => [
                    'class' => 'no-margin',
                ],
                'label_format' => 'create_client.%name%',
                'required' => false,
            ]);




        //TODO : afficher le total déjà distribué dans le label
        //TODO : créer une contrainte de non dépassement en cas de définition relatives

        if ($options['standalone']){
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

    }

}
