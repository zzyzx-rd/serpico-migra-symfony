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
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ParticipantMinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $organization = $options['organization'];
        $query = $options['query'];
        $currentUser = $options["currentUser"];
        $builder->addEventListener(FormEvents::PRE_SET_DATA, static function (FormEvent $event) use ($organization, $query, $currentUser) {

            $form = $event->getForm();
        
            $form->add('user', HiddenType::class,[])
            ->add('externalUser', HiddenType::class,[])
            ->add('team', HiddenType::class,[]);
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
        $resolver->setDefault('entity', 'activity');
        $resolver->setDefault('data_class', function (Options $options) {
            if ($options['entity'] === 'iprocess') {
                return IProcessParticipation::class;
            } else if ($options['entity'] === 'template') {
                return TemplateParticipation::class;
            }
            return Participation::class;
        });
        $resolver->setRequired('currentUser');
        $resolver->setDefault('organization', null);
        $resolver->setDefault('query', null);
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');
    }
}
