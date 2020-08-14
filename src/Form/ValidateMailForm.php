<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etudiant
 * Date: 07/12/2017
 * Time: 09:35
 */
namespace App\Form;

use App\Entity\WorkerIndividual;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;

class ValidateMailForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('male', ChoiceType::class,
            [
                'choices' => [
                    'M.' => 1,
                    'Mme' => 0,
                ],
                'expanded' => false,
                'multiple' => false,
                'attr' => [
                    'style' => 'display: block!important',
                ],
                'constraints' => [
                    new Assert\NotBlank,
                ],
                'label_format' => "worker_individual_data.%name%",
                'required' => true,
                'placeholder' => false,
            ])

            ->add('firstname', TextType::class,
            [
                'label_format' => "worker_individual_data.%name%",
                //'data' => 'A'
            ])

            ->add('lastname', TextType::class,
            [
                'label_format' => "worker_individual_data.%name%",
                //'data' => 'B'
            ])

            ->add('email', TextType::class,
            [
                'label_format' => "worker_individual_data.%name%",
                //'data' => 'a.b',
            ]);


        if ($options['standalone']){
            $builder->add('submit', SubmitType::class,[
                'label_format' => 'worker_individual_data.%name%',
                'attr' =>
                    [
                        'class' => 'validate-mail waves-effect waves-light btn-large blue darken-4',
                    ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class',WorkerIndividual::class);
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');
        $resolver->setDefault('workingIndividuals', null);

    }

}
