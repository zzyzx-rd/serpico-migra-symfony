<?php

namespace App\Form\Type;

use Controller\MasterController;
use Doctrine\ORM\EntityRepository;
use App\Entity\ActivityUser;
use App\Entity\IProcessActivityUser;
use App\Entity\Team;
use App\Entity\TemplateActivityUser;
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

class ParticipantManageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $currentUser = MasterController::getAuthorizedUser();
        if (!$currentUser instanceof User) {
            throw 'Authentication issue: no authorized user found';
        }
        $organization = $options['organization'];
        $query = $options['query'];

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($organization, $query, $currentUser) {

            $form = $event->getForm();

            if ($query == 'internal') {
                $form->add(
                    'directUser', EntityType::class,
                    [
                        'label_format' => 'participants.%name%',
                        'class' => User::class,
                        'choice_label' => 'invertedFullName',
                        'query_builder' => function (EntityRepository $er) use ($organization, $currentUser) {
                            $orgId = $organization ? $organization->getId() : $currentUser->getOrganization()->getId();
                            return $er->createQueryBuilder('u')
                                ->where("u.orgId = $orgId")
                                ->andWhere("u.deleted is NULL")
                                ->andWhere("u.lastname != 'ZZ'")
                                ->orderBy('u.lastname', 'asc');

                        },
                        'attr' => [
                            'class' => 'select-with-fa',
                        ],
                    ]
                );
            } else if ($query == 'external') {
                $form->add('directUser', EntityType::class,
                    [
                        'label_format' => 'participants.%name%',
                        'class' => User::class,
                        'choice_label' => function (User $u) {
                            if ($u->getLastname() == 'ZZ') {
                                return $u->getOrganization()->getCommname();
                            } else {
                                return $u->getInvertedFullName();
                            }
                        },
                        'choice_attr' => function(User $u) {
                            return $u->getLastname() == 'ZZ' ? ['class' => 'synth'] : [];
                        },
                        'query_builder' => function (EntityRepository $er) use ($organization, $currentUser) {
                            $orgId = $organization ? $organization->getId() : $currentUser->getOrganization()->getId();
                            return $er->createQueryBuilder('u')
                                ->innerJoin('App\Entity\Client', 'c', 'WITH', 'c.clientOrganization = u.orgId')
                                ->innerJoin('App\Entity\Organization', 'o', 'WITH', 'o.id = c.clientOrganization')
                                ->where("c.organization = $orgId")
                                ->andWhere('u.deleted is NULL')
                                ->orderBy('o.commname', 'asc')
                                ->addOrderBy('u.lastname', 'asc');
                        },
                        'attr' => [
                            'class' => 'select-with-fa',
                        ],
                    ]);
            } else if ($query == 'team') {
                $form->add(
                    'team', EntityType::class,
                    [
                        'label_format' => 'participants.%name%',
                        'class' => Team::class,
                        'choice_label' => 'name',
                        'query_builder' => function (EntityRepository $er) use ($organization, $currentUser) {
                            $orgId = $organization ? $organization->getId() : $currentUser->getOrganization()->getId();
                            return $er->createQueryBuilder('t')
                                ->where("t.organization = $orgId")
                                ->orderBy('t.name', 'asc');
                        },
                        'attr' => [
                            'class' => 'select-with-fa',
                        ],
                    ]
                );
            }
        });

        if ($query != 'external') {
            $builder->add('leader', CheckboxType::class,
                [
                    'attr' => [
                        'class' => 'filled-in elmt-is-leader',
                    ],
                    'label_format' => 'participants.%name%',
                    'required' => false,
                ]);
        }
        $builder->add('type', ChoiceType::class,
            [
                'choices' => [
                    'participants.active' => 1,
                    'participants.third_party' => 0,
                    'participants.passive' => -1,
                ],
                'label' => null,
            ])
            ->add('precomment', TextareaType::class,
                [
                    'label_format' => 'create_parameters.%name%',
                    'attr' => [
                        'class' => 'textarea-broaden',
                    ],
                    'required' => false,
                ]);

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
        $resolver->setDefault('elmt', 'activity');
        $resolver->setDefault('data_class', function (Options $options) {
            if ($options['elmt'] == 'iprocess') {
                return IProcessActivityUser::class;
            } else if ($options['elmt'] == 'template') {
                return TemplateActivityUser::class;
            }
            return ActivityUser::class;
        });
        $resolver->setDefault('organization', null);
        $resolver->setDefault('query', null);
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');
    }
}
