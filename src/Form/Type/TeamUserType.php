<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etudiant
 * Date: 07/12/2017
 * Time: 09:35
 */
namespace App\Form\Type;

use Controller\MasterController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use App\Entity\TeamUser;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class TeamUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
            
        $organization = $options['organization'];
        $query = $options['query'];
        $currentUser = $options["currentUser"];
        if (!$currentUser instanceof User) {
            throw 'Authentication issue: no authorized user found';
        }

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($organization, $query, $currentUser) {

            $form = $event->getForm();

            if ($query == 'internal') {

                $form->add('user', EntityType::class,
                [
                    'label_format' => 'participants.%name%',
                    'class' => User::class,
                    'choice_label' => 'invertedFullName',
                    'query_builder' => function (EntityRepository $er) use ($organization, $currentUser) {
                        $theOrganization = $organization ?: $currentUser->getOrganization();
                        return $er->createQueryBuilder('u')
                            ->where("u.organization = $theOrganization")
                            ->andWhere("u.deleted is NULL")
                            ->andWhere("u.lastname != 'ZZ'")
                            ->orderBy('u.lastname', 'asc');

                    },
                    'attr' => [
                        'class' => 'select-with-fa',
                    ],
                    
                ]);

            } else {
                $form->add('user', EntityType::class,
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
                            ->innerJoin('App\Entity\Client', 'c', 'WITH', 'c.clientOrganization = u.organization')
                            ->innerJoin('App\Entity\Organization', 'o', 'WITH', 'o.id = c.clientOrganization')
                            ->where("c.organization = $orgId")
                            ->andWhere('u.deleted is NULL')
                            ->orderBy('o.commname', 'asc')
                            ->addOrderBy('u.lastname', 'asc');
                    },
                ]);
            }
        });

        
        $builder->add('leader', CheckboxType::class,
            [
                'attr' => [
                    'class' => 'filled-in user-is-owner',
                ],
                'label_format' => 'participants.%name%',
                'required' => false,
            ]);


        if ($options['standalone']){
            $builder->add('submit', SubmitType::class,[
                'label' => "Sauvegarder les modifications",
                //'label_format' => 'create_user.%name%',
                'attr' =>
                    [
                        'class' => 'waves-effect waves-light btn-large blue darken-4 create-users',
                    ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', TeamUser::class);
        $resolver->setDefault('standalone', false);
        $resolver->setDefault('query','internal');
        $resolver->setDefault('organization',null);
        $resolver->setRequired("currentUser");
        $resolver->addAllowedTypes('standalone', 'bool');
    }

}
