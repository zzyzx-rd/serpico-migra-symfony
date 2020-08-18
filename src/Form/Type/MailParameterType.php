<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etudiant
 * Date: 07/12/2017
 * Time: 09:35
 */
namespace App\Form\Type;

use App\Entity\OTPUser;
use App\Entity\Position;
use App\Entity\User;
use App\Entity\Weight;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints as Assert;
use Validator\EmailUniqueness;

class MailParameterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('parameterKey', TextType::class,
            [
                'constraints' => [
                    new Assert\NotBlank,
                ],
                'label_format' => 'mails.testing.%name%',
                'required' => true,
            ])
            ->add('parameterValue', TextType::class,
                [
                    'constraints' => [
                        new Assert\NotBlank,
                    ],
                    'label_format' => 'mails.testing.%name%',
                    'required' => true,
                ]);


        //TODO : afficher le total déjà distribué dans le label
        //TODO : créer une contrainte de non dépassement en cas de définition relatives

        if ($options['standalone']){
            $builder->add('submit', SubmitType::class,[
                'label' => 'Soumettre',
                'attr' => [
                    'class' => 'btn-large waves-effect waves-light teal lighten-1',
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
