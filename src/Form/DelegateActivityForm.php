<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 27/12/2017
 * Time: 16:11
 */

namespace App\Form;

use App\Controller\MasterController;
use App\Validator\UniquePerOrganization;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\User;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class DelegateActivityForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        if($options['request']){

            $builder->add('ownCreation', CheckboxType::class,
                [
                    'data' => true,
                    'label_format' => 'activities.create.%name%',
                    'attr' => [
                        'class' => 'filled-in'
                    ]
                ]);

        }

        $submitBtnClass = ($options['request']) ? 'validate-button' : 'delegate-button';

        $builder->addEventListener(FormEvents::PRE_SET_DATA, static function (FormEvent $event) use ($options) {

            $form = $event->getForm();
           
            $form->add(
                'activityLeader', EntityType::class,
                [
                    'label_format' => 'activities.create.%name%',
                    'class' => User::class,
                    'choice_label' => 'invertedFullName',
                    'query_builder' => static function (EntityRepository $er) use ($options) {
                        $currentUser = $options['currentUser'];
                        return $er->createQueryBuilder('u')
                            ->where("u.organization = ".$currentUser->getOrganization())
                            ->andWhere("u.deleted is NULL")
                            ->andWhere("u.lastname != 'ZZ'")
                            ->andWhere("u.firstname != '".$currentUser->getFirstname()."' AND u.lastname != '".$currentUser->getLastname()."'")
                            ->orderBy('u.lastname', 'asc');

                    },
                ]
            );
           
        });
        
        $builder
            ->add('activityName', TextType::class,
            [
                'label_format' => 'activities.create.%name%',
                'constraints' => [
                    new Assert\NotBlank,
                    new UniquePerOrganization([
                        'organization' => $options["currentUser"]->getOrganization(),
                        'entity' => 'activity',
                        'element' => null,
                        'property' => 'name',
                        'message' => 'create_parameters.doublon_activity_name',
                        ]
                    ),
                ],
            ])
            ->add('activityDescription', TextareaType::class,
                [
                    'label_format' => 'activities.request.%name%',
                    'attr' => [
                        'style' => 'min-height:100px;'
                    ],
                    'required' => false,
                ])
            /*
            ->add('activityLeader', ChoiceType::class,
            [
                'label_format' => 'activities.create.%name%',
                'choices' => array_combine($values,$keys),
                'expanded' => false,
                'multiple' => false,
                //'choices_as_values' => false,
                'attr' => [
                    'style' => 'margin-bottom: 15px; display: block!important',
                    'disabled' => ($options['request']) ? 'disabled' : false,
                ],
            ])*/;
        if ($options['standalone']){
            $builder->add('submit', SubmitType::class,[
                'label_format' => 'activities.create.delegate_btn_msg',
                'attr' => [
                    'class' => 'btn '.$submitBtnClass.' waves-effect waves-light teal lighten-1',
                ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('currentUser');
        $resolver->setDefault('standalone', false);
        $resolver->setDefault('request', false);
        $resolver->addAllowedTypes('standalone', 'bool');
    }

}
