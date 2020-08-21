<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 28/12/2017
 * Time: 15:25
 */

namespace App\Form;

use App\Entity\WorkerFirmSector;
use App\Entity\Country;
use App\Entity\State;
use App\Entity\City;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormBuilderInterface;
use App\Validator\UBEmpGreaterThanLBEmp;

class SearchWorkerForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $workerFirmSectors = $em->getRepository(WorkerFirmSector::class)->findAll();
        $existingCountries = $em->getRepository(Country::class)->findAll();
        $existingStates = $em->getRepository(State::class)->findAll();
        $existingCities = $em->getRepository(City::class)->findAll();
        $countries =
        $arraySearch = [];
        for($i=0; $i<10000; $i++){
            $arraySearch[] = $i;
        }

        /*

        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT(wf.country)')
            ->from('App\Entity\WorkerFirm', 'wf');

        $existingCountries = $qb->getQuery()->getResult();

        $qb->select('DISTINCT(wg.state)')
        ->from('App\Entity\WorkerFirm', 'wg');

        $existingStates = $qb->getQuery()->getResult();

        $qb->select('DISTINCT(wh.city)')
        ->from('App\Entity\WorkerFirm', 'wh');

        $existingCities = $qb->getQuery()->getResult();

        */

        $WFSKeys[] = 0;
        $WFSValues[] = "(Tous)";
        $countryKeys[] = 0;
        $countryValues[] = "(Tous)";
        $stateKeys[] = 0;
        $stateValues[] = "(Tous)";
        $cityKeys[] = 0;
        $cityValues[] = "(Toutes)";

        foreach($workerFirmSectors as $key => $workerFirmSector){

            $WFSKeys[] = $workerFirmSector->getId();
            $WFSValues[] = $workerFirmSector->getName();
        }

        foreach($existingCountries as $key => $existingCountry){

            $countryKeys[] = $existingCountry->getId();
            $countryValues[] = $existingCountry->getName();
        }

        foreach($existingStates as $key => $existingState){

            $stateKeys[] = $existingState->getId();
            $stateValues[] = $existingState->getName();
        }

        foreach($existingCities as $key => $existingCity){

            $cityKeys[] = $existingCity->getId();
            $cityValues[] = $existingCity->getName();
        }

        $builder->add('fullName', TextType::class,
            [
                'label_format' => 'worker_search.%name%',
                'required' => false,
            ])
        ->add('firmName', TextType::class,
            [
                'label_format' => 'worker_search.%name%',
                'required' => false,
                'attr' => [
                    'style' => 'margin: 0',
                ],
            ])

        ->add('position', TextType::class,
        [
            'label_format' => 'worker_search.%name%',
            'required' => false,
        ])

        ->add('country', ChoiceType::class,
        [
            'choices' => array_combine($countryValues,$countryKeys),
            'expanded' => false,
            'multiple' => false,
            'attr' => [
                'style' => 'display: block!important',
            ],
            'constraints' => [
                new Assert\NotBlank,
            ],
            'label_format' => "worker_search.%name%",
            'required' => false,
            'placeholder' => false,
            'data' => 0,
        ])

        ->add('state', ChoiceType::class,
        [
            'choices' => array_combine($stateValues,$stateKeys),
            'expanded' => false,
            'multiple' => false,
            'attr' => [
                'style' => 'display: block!important',
            ],
            'constraints' => [
                new Assert\NotBlank,
            ],
            'label_format' => "worker_search.%name%",
            'required' => false,
            'placeholder' => false,
            'data' => 0,
        ])

        ->add('city', ChoiceType::class,
        [
            'choices' => array_combine($cityValues,$cityKeys),
            'expanded' => false,
            'multiple' => false,
            'attr' => [
                'style' => 'display: block!important',
            ],
            'constraints' => [
                new Assert\NotBlank,
            ],
            'label_format' => "worker_search.%name%",
            'required' => false,
            'placeholder' => false,
            'data' => 0,
        ])



        ->add('HQLocation', TextType::class,
        [
            'label_format' => 'worker_search.%name%',
            'required' => false,
            'attr' => [
                'style' => 'margin: 0',
            ],
        ])

        ->add('fType', ChoiceType::class,
        [
            'choices' => [
                '(Tous)' => 99,
                'Non lucratif' => -3,
                'Administration publique' => -2,
                'Établissement éducatif' => -1,
                'Travailleur indépendant ou profession libérale' => 0,
                'Entreprise individuelle' => 1,
                'Société civile/Société commerciale/Autres types de sociétés' => 2,
                'Société de personnes (associés)' => 3,
                'Société cotée en bourse' => 4,

            ],
            'expanded' => false,
            'multiple' => false,
            'attr' => [
                'style' => 'display: block!important',
            ],
            'constraints' => [
                new Assert\NotBlank,
            ],
            'label_format' => "worker_search.%name%",
            'required' => false,
            'placeholder' => false,
            'data' => 99,
        ])

        ->add('fSizeFrom', ChoiceType::class,
        [
            'choices' => [
                '(Toutes tailles)' => -1,
                '1 employé' => 0,
                '10 employés' => 1,
                '50 employés' => 2,
                '200 employés' => 3,
                '500 employés' => 4,
                '1000 employés' => 5,
                '5000 employés' => 6,
                '10000 employés' => 7,
            ],
            'expanded' => false,
            'multiple' => false,
            'attr' => [
                'style' => 'display: block!important',
            ],
            'constraints' => [
                new Assert\NotBlank,
            ],
            'label_format' => "worker_search.%name%",
            'required' => false,
            'placeholder' => false,
            'data' => -1,
        ])

        ->add('fSizeTo', ChoiceType::class,
        [
            'choices' => [
                '(Toutes tailles)' => -1,
                '9 employés' => 0,
                '49 employés' => 1,
                '199 employés' => 2,
                '499 employés' => 3,
                '999 employés' => 4,
                '4999 employés' => 5,
                '9999 employés' => 6,
                'Sky is the limit' => 7,
            ],
            'expanded' => false,
            'multiple' => false,
            'attr' => [
                'style' => 'display: block!important',
            ],
            'constraints' => [
                new Assert\NotBlank,
                new UBEmpGreaterThanLBEmp,
            ],
            'label_format' => "worker_search.%name%",
            'required' => false,
            'placeholder' => false,
            'data' => -1,
        ])

        ->add('fSector', ChoiceType::class,
        [
            'choices' => array_combine($WFSValues,$WFSKeys),
            'expanded' => false,
            'multiple' => false,
            'attr' => [
                'style' => 'display: block!important',
            ],
            'constraints' => [
                new Assert\NotBlank,
            ],
            'label_format' => "worker_search.%name%",
            'required' => false,
            'placeholder' => false,
            'data' => 0,
        ])

        ->add('currentOnly', CheckboxType::class,
        [
            'label_format' => 'worker_search.%name%',
            'required' => false,
        ])

        ->add('submit', SubmitType::class,
        [
            'label_format' => 'worker_search.%name%',
            'attr' => [
                'class' => 'btn-large waves-effect waves-light teal lighten-1',
            ]
        ]);




    }

    public function configureOptions(OptionsResolver $resolver)
    {
        //$resolver->addAllowedTypes('standalone', 'bool');
        //$resolver->setRequired('startdate');
        //$resolver->setRequired('enddate');
        //$resolver->setRequired('gstartdate');
    }

    /*

    public function getParent()
    {
        return FormType::class;
    }

    public function getName() {
        return 'stage';
    }*/
}
