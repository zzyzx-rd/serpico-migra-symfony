<?php
/**
 * Created by IntelliJ IDEA.
 * User: lawre
 * Date: 14/05/2018
 * Time: 10:06
 */

namespace App\Form;

use App\Entity\TemplateActivity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AddTemplateForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder->add('name', TextType::class,
            [
                'label_format' => 'create_template.%name%',
                'constraints' => [
                    new Assert\NotBlank
                ],
            ]);


        if($options['standalone']){
            $builder->add('submit', SubmitType::class,[
                'label_format' => 'create_position.%name%',
                'attr' => [
                    'class' => 'btn btn-large add-template'
                ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class',TemplateActivity::class);
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');
    }
}
