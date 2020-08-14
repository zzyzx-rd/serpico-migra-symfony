<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 27/12/2017
 * Time: 16:11
 */

namespace App\Form;

use Controller\MasterController;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\App\Form\Type\EntityType;
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
use Validator\UniquePerOrganization;

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

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {

            $form = $event->getForm();
           
            $form->add(
                'activityLeader', EntityType::class,
                [
                    'label_format' => 'activities.create.%name%',
                    'class' => User::class,
                    'choice_label' => 'invertedFullName',
                    'query_builder' => function (EntityRepository $er) {
                        $currentUser = MasterController::getAuthorizedUser();
                        return $er->createQueryBuilder('u')
                            ->where("u.orgId = ".$currentUser->getOrgId())
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
                        'organization' => MasterController::getAuthorizedUser()->getOrganization(),
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
        $resolver->setRequired('app');
        $resolver->setDefault('standalone', false);
        $resolver->setDefault('request', false);
        $resolver->addAllowedTypes('standalone', 'bool');
    }

}
