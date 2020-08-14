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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\Type\WorkerExperienceType;
use Doctrine\Common\Collections\ArrayCollection;

class UpdateWorkerIndividualForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

            /*
            $workerIndividual = $options['workerIndividual'];
            $mailPrefix = $options['mailPrefix'];
            $mailSuffix = $options['mailSuffix'];
            if($workerIndividual != null){
                if($workerIndividual->getFirstname() == null){
                    $fullNameComponents = explode(" ", $workerIndividual->getFullName());
                    $firstname = $fullNameComponents[0];
                    array_shift($fullNameComponents);
                    $lastname = implode(" ",$fullNameComponents);
                } else {
                    $firstname = $workerIndividual->getFirstname();
                    $lastname = $workerIndividual->getLastname();
                }

            } else {
                $firstname = null;
                $lastname = null;
            }

            if($workerIndividual != null && $mailPrefix != null){
                if($workerIndividual->getEmail() == null){

                    switch($mailPrefix){
                        case 1:
                            $indivMailPrefix = strtolower($firstname).'.'.strtolower($lastname);
                            break;
                        case 2:
                            $indivMailPrefix = strtolower($firstname[0]).strtolower($lastname);
                            break;
                        case 3:
                            $indivMailPrefix = strtolower($firstname[0]).'.'.strtolower($lastname);
                            break;
                        case 4:
                            $indivMailPrefix = strtolower($firstname[0]).strtolower($lastname[0]);
                            break;
                        default:
                            break;
                    }
                    $email = $indivMailPrefix.'@'.$mailSuffix;

                } else {
                    $email = $workerIndividual->getEmail();
                }
            } else {
                $email = null;
            }
            */

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
                'required' => false,
                //'data' => $firstname,
            ])

            ->add('lastname', TextType::class,
            [
                'label_format' => "worker_individual_data.%name%",
                'required' => false,
                //'data' => $lastname,
            ])

            ->add('email', TextType::class,
            [
                'label_format' => "worker_individual_data.%name%",
                'required' => false,
                //'data' => $email,
            ])

            /*

            ->add('url', TextType::class,
            [
                'label_format' => "worker_individual_data.%name%",
                'required' => false,
            ])
            */
            ;

            $builder->add('experiences', CollectionType::class, [
                    'entry_type' => WorkerExperienceType::class,
                    'prototype'    => true,
                    'by_reference' => false,
                    'allow_delete' => true,
                    'allow_add' => true,
                    'label' => false,
                ]);

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
        $resolver->setDefault('data_class',WorkerIndividual::class);
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');
        $resolver->setDefault('workerIndividual', null);
        $resolver->setDefault('mailPrefix', null);
        $resolver->setDefault('mailSuffix', null);
    }

}
