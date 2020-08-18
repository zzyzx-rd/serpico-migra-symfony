<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 28/12/2017
 * Time: 15:25
 */

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\App\FormFormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\App\FormAbstractType;
use Doctrine\ORM\EntityRepository;
use App\Entity\InstitutionProcess;
use App\Entity\Process;
use Validator\UniquePerOrganization;

class AddProcessForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('name', TextType::class,
        [
            'label_format' => 'institutions.add_process.%name%',
            'constraints' => [
                new Assert\NotBlank,
                new UniquePerOrganization([
                    'organization' => $options['organization'],
                    'entity' => 'institutionProcess',
                    'element' => $builder->getData(),
                    'property' => 'name',
                    'message' => "erreur"
                ]),
            ],
        ])->add('parent', EntityType::class,
        [
            'label_format' => 'institutions.add_process.%name%',
            'class' => $options['elmt'] === 'iprocess' ? InstitutionProcess::class : Process::class,
            'choice_label' => 'name',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('p')
                    ->orderBy('p.name', 'asc');
            },
            'required' => false,
            'placeholder' => '(Aucune rubrique)',
        ]);

        if($options['standalone']){
            $builder->add('submit', SubmitType::class,[
                'label_format' => 'institutions.add_process.%name%',
                'attr' => [
                    'class'=> 'modal-close waves-effect waves-green btn-large process-request'
                ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
        ->setDefaults([
            'elmt' => 'iprocess',
            'data_class' => function (Options $options) {
                return $options['elmt'] === 'iprocess'
                       ? InstitutionProcess::class
                       : Process::class;
            },
            
            'app' => null,
            'organization' => null,
            'standalone' => true,
        ]);
        $resolver->setDefault('standalone', false);
        $resolver->setDefault('organization', null);
        $resolver->addAllowedTypes('standalone', 'bool');
    }

}
