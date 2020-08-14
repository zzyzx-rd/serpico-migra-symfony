<?php

namespace App\Form\Type;

use Doctrine\ORM\EntityRepository;
use App\Entity\CriterionName;
use App\Entity\Target;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TargetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('cName', EntityType::class,
            [
                'class'         => CriterionName::class,
                'choice_label'  => 'name',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('c')
                        ->where('c.organization =' . $options['organization'])
                        ->orderBy('c.name', 'ASC');
                },
                'attr'          => [
                    'style' => 'display: block!important; font-family: FontAwesome',
                ],
            ])
            ->add('sign', ChoiceType::class,
                [
                    'attr'    => [
                        'class' => 'forceCommentSign',
                        'style' => 'position: relative; display: block!important;',
                    ],
                    'label'   => '',
                    'choices' => [
                        'targets.strictly_lower_than'    => '-2',
                        'targets.lower_than_or_equal_to' => '-1',
                        'targets.equal_to'               => '0',
                        'targets.superior_or_equal_to'   => '1',
                        'targets.strictly_superior_to'   => '2',
                    ],
                ])
            ->add(
                'value', NumberType::class
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', Target::class);
        $resolver->setDefault('organization', null);
    }
}
