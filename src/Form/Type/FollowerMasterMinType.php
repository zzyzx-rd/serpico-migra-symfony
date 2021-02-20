<?php

namespace App\Form\Type;

use Controller\MasterController;
use Doctrine\ORM\EntityRepository;
use App\Entity\Participation;
use App\Entity\IProcessParticipation;
use App\Entity\Team;
use App\Entity\TemplateParticipation;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Client;
use App\Entity\Organization;
use App\Entity\UserMaster;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class FollowerMasterMinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->addEventListener(FormEvents::PRE_SET_DATA, static function (FormEvent $event){

            $form = $event->getForm();
            $form->add('userPart', HiddenType::class,[
                'property_path' => 'user',
                'attr' => ['class' => 'u']
            ])
            ->add('externalUserPart', HiddenType::class,[
                'mapped' => false,
                'attr' => ['class' => 'eu']
            ])
            ->add('email', HiddenType::class,[
                'mapped' => false,
                'attr' => ['class' => 'em']
            ]);

        });

        if ($options['standalone']) {
            $builder->add('submit', SubmitType::class, [
                'label_format' => 'user_update.%name%',
                'attr' => [
                    'class' => 'btn-large waves-effect waves-light teal lighten-1 user-submit',
                ],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', UserMaster::class);
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');
    }
}
