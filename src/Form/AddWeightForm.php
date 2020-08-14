<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 16/07/2018
 * Time: 11:37
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AddWeightForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder->add('weight', IntegerType::class,
            [
                'constraints' => [
                    new Assert\NotBlank,
                    new Assert\GreaterThan(0),
                ],
                'label_format' => 'create_user.%name%',
            ]);


        if($options['standalone']){
            $builder->add('submit', SubmitType::class,[
                'label_format' => 'create_position.%name%',
                'attr' => [
                    'class'=> 'btn btn-large weight-submit'
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
