<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etudiant
 * Date: 18/12/2017
 * Time: 17:51
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\User;

class CreateActivityForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //print_r($options['users'][0]->getId());
        //die;
        /*foreach($options['users'] as $user) {
            $builder->add('id', ButtonType::class, [

                'attr' => [
                    'class' => 'action-button waves-effect waves-light btn',
                    'name' => $user->getId()
                ],

                'label' => 'Participants'
            ]);
        }*/


        $builder->add('id', ButtonType::class, [

            'attr' => [
                'class' => 'action-button waves-effect waves-light btn',
                'name' => $options['users'][0]->getId()
            ],

            'label' => 'Participants'
        ])->add('firstname', ButtonType::class, [

            'attr' => [
                'class' => 'action-button waves-effect waves-light btn',
                'name' => $options['users'][1]->getId()
            ],

            'label' => 'Participants'
        ]);

        if ($options['standalone']) {
            $builder->add('submit', SubmitType::class,
                [
                    'label' => 'Create activity'
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class',User::class);
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');
        $resolver->setRequired('users');
    }
}