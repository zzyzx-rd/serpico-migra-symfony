<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 16/07/2018
 * Time: 11:37
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CreateCriterionForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('name', TextType::class,
            [
                'constraints' => [
                    new Assert\NotBlank,
                ],
                'label_format' => 'criteria.create_criterion.%name%',
            ]);

        $builder->add('type', ChoiceType::class,
            [
                'choices' => [
                    'criteria.create_criterion.hard_skills_option' => 2,
                    'criteria.create_criterion.soft_skills_option' => 1,
                ],
                'constraints' => [
                    new Assert\NotBlank,
                ],
                'attr' => [
                    'style' => 'display: block!important; font-family: Roboto, FontAwesome',
                ],
                'expanded' => false,
                'multiple' => false,
                'choice_translation_domain' => true,
                'label_format' => 'criteria.create_criterion.%name%',
            ]);

        if($options['standalone']){
            $builder->add('submit', SubmitType::class,[
                'label_format' => 'criteria.create_criterion.%name%',
                'attr' => [
                    'class'=> 'btn btn-large criterion-submit'
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
