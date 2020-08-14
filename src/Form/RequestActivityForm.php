<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 27/12/2017
 * Time: 16:11
 */

namespace App\Form;

use Controller\MasterController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\User;


class RequestActivityForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $app = $options['app'];
        $em = $app['orm.em'];
        $repoU = $em->getRepository(User::class);
        $orgId = MasterController::getAuthorizedUser($app)->getOrgId();
        $users = $repoU->findByOrgId($orgId);
        $keys = [];
        $values = [];
        foreach($users as $user){
            if($user->getRole() != 3){
                $keys[] = $user->getId();
                $values[] = $user->getFirstname().' '.$user->getLastname();
            }
        }


        $builder->add('activityName', TextType::class,
            [
                'label_format' => 'activities.request.%name%',
                'constraints' => [
                    new Assert\NotBlank
                ],
            ])
            ->add('activityDescription', TextareaType::class,
                [
                    'label_format' => 'activities.request.%name%',
                    'attr' => [
                        'style' => 'min-height:100px;'
                    ],
                ])
            ->add('requestType', ChoiceType::class,
                [
                    'label_format' => 'activities.request.%name%',
                    'choices' => [
                        'activities.request.general_recipients_option' => 1,
                        'activities.request.specific_recipients_option' => 2,
                    ],
                    'expanded' => true,
                    'multiple' => false,
                    //'choices_as_values' => false,
                    'constraints' => [
                        new Assert\NotBlank
                    ],
                    'data' => 1
                ])
            ->add('specificRecipients', ChoiceType::class,
            [
                'label_format' => 'activities.request.%name%',
                'choices' => array_combine($values,$keys),
                'expanded' => false,
                'multiple' => true,
                //'choices_as_values' => false,
                'constraints' => [
                    new Assert\NotBlank
                ],
                'attr' => [
                    'style' => 'margin-bottom: 15px; display: block!important',
                    'class' => 'validators'
                ],
                'disabled' => true,
            ])
            ->add('discloseRequester', CheckboxType::class,
            [
                'label_format' => 'activities.request.%name%',
                'data' => true,
                'attr' => [
                    'class' => 'filled-in',
                    'style' => 'margin-top: 10px;',
                ]
            ]);

        if ($options['standalone']){
            $builder->add('submit', SubmitType::class,[
                'label_format' => 'activities.request.%name%',
                'attr' => [
                    'class' => 'btn-large delegate-button waves-effect waves-light teal lighten-1',
                ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('app');
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');
    }

}
