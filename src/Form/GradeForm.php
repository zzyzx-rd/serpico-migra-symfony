<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 17/12/2017
 * Time: 23:55
 */

namespace App\Form;
use App\Entity\Grade;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints as Assert;

class GradeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


                $builder->add('value', NumberType::class,
                    [
                        'constraints' => [
                            new Assert\NotBlank,
                        ],

                    ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class',Grade::class);
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');
    }

}
