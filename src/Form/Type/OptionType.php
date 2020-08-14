<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etudiant
 * Date: 07/12/2017
 * Time: 09:35
 */
namespace App\Form\Type;

use App\Entity\OrganizationUserOption;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Validator\Constraints as Assert;

class OptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($builder) { 
            /** @var OrganizationUserOption $data */ 
            $data = $event->getData();
            $form = $event->getForm();
            $optionName = $data->getOName()->getName();
            switch($optionName){
                case 'enabledSuperiorSubRights' :
                case 'enabledSuperiorSettingTargets' :
                case 'enabledSuperiorModifySubordinate' :
                case 'enabledSuperiorOverviewSubResults' :
                case 'enabledUserSeeAllUsers' :
                case 'enabledUserCreatingUser' :
                case 'enabledUserSeeSnapshotPeersResults' :
                case 'enabledUserSeeSnapshotSupResults' :
                case 'enabledCNamesOutsideCGroups' :
                case 'enabledUserSeeRanking' :
                    $form->add('optionTrue', CheckboxType::class, [
                        'label_format' => 'firm_settings.users.privileges.'.strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $optionName)),
                        'required' => true,
                        'attr' => [
                            'class' => 'filled-in',
                        ]
                    ]);
                    break;
                case 'mailDeadlineNbDays' : 
                    $form->add('optionFValue', IntegerType::class, [
                        'label_format' => 'firm_settings.users.mail_deadline_nb_days',
                        'required' => true,
                        'constraints' => [
                            new Assert\GreaterThan(0),
                        ],
                        'attr' => [
                            'style' => 'margin: auto auto auto 23px; max-width:20%',
                            'min' => 1,
                        ],
                        ]);
                    break;
                case 'activitiesAccessAndResultsView' :
                    $form->add('optionIValue', ChoiceType::class,
                        [
                            'attr' => [
                                'style' => 'position: relative;margin-bottom:0px'
                            ],
                            'label' => null,
                            'choices' => [
                                'firm_settings.users.privileges.'.strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $optionName)).'.activity_access.firm' => 1,
                                'firm_settings.users.privileges.'.strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $optionName)).'.activity_access.department' => 2,
                                'firm_settings.users.privileges.'.strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $optionName)).'.activity_access.own' => 3,
                            ]
                        ])
                    ->add('optionSecondaryIValue', ChoiceType::class,
                        [
                            'attr' => [
                                'style' => 'position: relative;margin-bottom:0px'
                            ],
                            'label' => null,
                            'choices' => [
                                //'firm_settings.users.privileges.'.strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $optionName)).'.results_access.live' => 0,
                                //'firm_settings.users.privileges.'.strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $optionName)).'.results_access.incomplete' => 1,
                                'firm_settings.users.privileges.'.strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $optionName)).'.results_access.unpublished' => 2,
                                'firm_settings.users.privileges.'.strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $optionName)).'.results_access.published' => 3,
                            ]
                        ])
                        ->add('optionTrue', ChoiceType::class,
                        [
                            'attr' => [
                                'style' => 'position: relative;margin-bottom:0px'
                            ],
                            'label' => null,
                            'choices' => [
                                'firm_settings.users.privileges.'.strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $optionName)).'.scope.all_participations' => 1,
                                'firm_settings.users.privileges.'.strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $optionName)).'.scope.own_participations' => 0,
                            ]
                        ])
                        
                        ->add('optionFValue', ChoiceType::class,
                        [
                            'attr' => [
                                'style' => 'position: relative;margin-bottom:0px'
                            ],
                            'label' => null,
                            'choices' => [
                                'firm_settings.users.privileges.'.strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $optionName)).'.results_details.detailed' => 1,
                                'firm_settings.users.privileges.'.strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $optionName)).'.results_details.consolidated' => 0,
                            ]
                        ])
                        ->add('optionSValue', ChoiceType::class,
                        [
                            'attr' => [
                                'style' => 'position: relative;margin-bottom:0px'
                            ],
                            'label' => null,
                            'choices' => [
                                'firm_settings.users.privileges.'.strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $optionName)).'.participation_condition.none' => 'none',
                                'firm_settings.users.privileges.'.strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $optionName)).'.participation_condition.owner' => 'owner',
                                'firm_settings.users.privileges.'.strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $optionName)).'.participation_condition.participant' => 'participant',
                            ]
                        ])
                        ;
                        break;
                default:
                   /* $form->add('optionFValue', NumberType::class, [
                        'label_format' => 'firm_settings.users.%name%',
                        'required' => true,
                        ]);*/
                    break;
                        
            }
            
        });

        /*
        $builder->get('optionTrue')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($builder) { 
            
            $option = $event->getForm()->getData();
            $form = $event->getForm()->getParent();
            
            if($option->getOName()->getName() != 'enabledCreatingUser'){
                $form->add('optionFValue', NumberType::class, [
                    'label_format' => 'firm_settings.users.%name%',
                    'required' => true,
                    ]);
            } else {
                $form->add('optionTrue', CheckboxType::class, [
                    'label_format' => 'firm_settings.users.enabled_creating_user',
                    'required' => true,
                    'attr' => [
                        'class' => 'filled-in',
                        'onclick' => 'this.previousSibling.value=1-this.previousSibling.value',
                    ]
                    ]);
            }   
        });
        */


        /*$builder->add('value', NumberType::class, [
        'label_format' => 'firm_settings.users.%name%',
        'required' => true,
        ]);
        */

        

        /*    
        if ($options['standalone']){
            $builder->add('submit', SubmitType::class,[
                'label_format' => 'user_update.%name%',
                'attr' => [
                    'class' => 'btn-large waves-effect waves-light teal lighten-1 firm-settings-submit',
                ]
            ]);
        }*/
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', OrganizationUserOption::class);
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');
    }

}
