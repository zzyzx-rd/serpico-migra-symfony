<?php

namespace App\Form\Type;

use App\Entity\DocumentAuthor;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;

class DocumentAuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $currentUser = $options['currentUser'];

        $builder->add('user', EntityType::class,
            [
                'label_format' => 'participants.%name%',
                'class' => User::class,
                'choice_label' => 'invertedFullName',
                'query_builder' => function (EntityRepository $er) use ($currentUser) {
                    $theOrganization = $currentUser->getOrganization();
                    return $er->createQueryBuilder('u')
                        ->where("u.organization = $theOrganization")
                        ->andWhere("u.deleted is NULL")
                        ->andWhere("u.lastname != 'ZZ'")
                        ->orderBy('u.lastname', 'asc');

                },
                'attr' => [
                    'class' => 'select-with-fa',
                ],
                
            ]
        );



        if ($options['standalone']){
            $builder->add('submit', SubmitType::class,[
                'label' => 'Soumettre',
                'attr' => [
                    'class' => 'btn-large waves-effect waves-light teal lighten-1 user-submit',
                ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class',DocumentAuthor::class);
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');
        $resolver->setDefault('currentUser',null);

    }

}
