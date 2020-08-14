<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etudiant
 * Date: 07/12/2017
 * Time: 09:35
 */
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\WorkerExperience;

class WorkerExperienceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
            
            $builder->add('active', CheckboxType::class,
            [
                'label_format' => "worker_individual_data.experience.%name%",
                'attr' => [
                    'class' => 'filled-in'
                ],
                'required' => false,
            ])

            ->add('position', TextType::class,
            [
                'label_format' => "worker_individual_data.experience.%name%",
            ])

            ->add('firm', TextType::class,
            [
                'label_format' => "worker_individual_data.experience.%name%",
            ])

            ->add('location', TextType::class,
            [
                'label_format' => "worker_individual_data.experience.%name%",
                'required' => false,
            ])
            
            ->add('startdate', DateTimeType::class,
            [
                'format' => 'dd/MM/yyyy',
                'widget' => 'single_text',
                'label_format' => 'worker_individual_data.experience.%name%',
                'html5' => false,
                'attr' => ['class' => 'dp-start'],
                //'data' => $options['startdate'],
                /*'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'A recurring activity must have a startdate'
                    ]),
                ],
                ¨*/
                'required' => false,
            ])

            ->add('enddate', DateTimeType::class,
            [
                'format' => 'dd/MM/yyyy',
                'widget' => 'single_text',
                'label_format' => 'worker_individual_data.experience.%name%',
                'html5' => false,
                'attr' => ['class' => 'dp-end'],
                //'data' => $options['enddate'],
                'required' => false,
            ])
            ;


        if ($options['standalone']){
            $builder->add('submit', SubmitType::class,[
                'label' => "Sauvegarder les modifications",
                //'label_format' => 'create_user.%name%',
                'attr' =>
                    [
                        'class' => 'waves-effect waves-light btn-large blue darken-4 create-users',
                    ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', WorkerExperience::class);
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');
    }

}
