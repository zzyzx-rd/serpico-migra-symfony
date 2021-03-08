<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etudiant
 * Date: 07/12/2017
 * Time: 09:35
 */
namespace App\Form;

use App\Entity\Country;
use App\Entity\WorkerFirm;
use App\Entity\WorkerFirmSector;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\Type\ClientUserMType;
use App\Form\Type\UserMType;
use App\Form\Type\ActivityNameType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class UpdateWorkerFirmForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

            $builder->add('commonName', TextType::class,
            [
                'label_format' => "worker_firm_data.%name%",
                'constraints' => [
                    new Assert\NotBlank,
                ],
            ])

            ->add('parent', HiddenType::class,
            [
            

            ])

            ->add('mainSector', EntityType::class,
            [
                'label_format' => "worker_firm_data.%name%",
                'class' => WorkerFirmSector::class,
                'choice_label' => 'name',
                'query_builder' => static function (EntityRepository $er) {
                    return $er->createQueryBuilder('wfs')
                        ->orderBy('wfs.name', 'asc');
                },
                'constraints' => [
                    new Assert\NotBlank,
                ],
            ])

            ->add('website', TextType::class,
            [
                'label_format' => "worker_firm_data.%name%",
                'required' => false,

            ])

            ->add('mailPrefix', ChoiceType::class, [
                'choices' => [
                    'prenom.nom' => 1,
                    'pnom' => 2,
                    'p.nom' => 3,
                    'pn' => 4,
                ],
                'expanded' => false,
                'multiple' => false,
                'constraints' => [
                    new Assert\NotBlank,
                ],
                'label_format' => "worker_firm_data.%name%",
                'required' => false,
                'placeholder' => false,
            ])

            ->add('suffix', TextType::class,
            [
                'label_format' => "worker_firm_data.%name%",
                'required' => false,
            ])

            ->add('url', TextType::class,
            [
                'label_format' => "worker_firm_data.%name%",
                'required' => false,
            ])

            ->add('size', ChoiceType::class, [
                'choices' => [
                    '1-10 employés' => 0,
                    '10-50 employés' => 1,
                    '50-200 employés' => 2,
                    '200-500 employés' => 3,
                    '500-1000 employés' => 4,
                    '1000-5000 employés' => 5,
                    '5k-10k employés' => 6,
                    '10k+ employés' => 7,
                ],
                
                'constraints' => [
                    new Assert\NotBlank,
                ],
                'label_format' => "worker_firm_data.%name%",
                'required' => false,
                'placeholder' => false,
            ])

            ->add('creationDate', DateTimeType::class,
            [
                'format' => 'dd/MM/yyyy',
                'widget' => 'single_text',
                'label_format' => 'worker_firm_data.%name%',
                'html5' => false,
                'attr' => ['class' => 'dp-gstart'],
                'constraints' => [
                    new Assert\NotBlank,
                    //new GSDGreaterThanSD
                ],
                'required' => false,
            ])

            ->add('HQCity', TextType::class,
            [
                'label_format' => "worker_firm_data.%name%",
            ])

            ->add('HQState', TextType::class,
            [
                'label_format' => "worker_firm_data.%name%",
            ])

            ->add('country', EntityType::class,
            [
                'label_format' => "worker_firm_data.%name%",
                'class' => Country::class,
                'choice_label' => 'name',
                'query_builder' => static function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'asc');
                },
                'constraints' => [
                    new Assert\NotBlank,
                ],
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
        $resolver->setDefault('data_class',WorkerFirm::class);
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');
        $resolver->setDefault('organization', null);

    }

}
