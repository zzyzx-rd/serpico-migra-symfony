<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 16/07/2018
 * Time: 11:37
 */

namespace App\Form;

use App\Entity\Activity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ActivityReportForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {



        $choices = [];

        $lastChoices = [];
        $activity = $options['activity'];

        // Building report choices
        $activityStages = [];
        $choices['report.full'] = $activity->getName();


        foreach($activity->getStages() as $stage){

            $suffix = ($stage->getStatus()<2) ? '(*)' : '';

            $lastChoices[$stage->getName().$suffix] = $stage->getName();
            //$stageCriteria = [];
            $criteria = [];
            foreach($stage->getCriteria() as $criterion){
                $criteria[$criterion->getName().$suffix] = $criterion->getName();
            }
            $lastChoices['- '.$stage->getName().' -'] = $criteria;
        }

        $choices['- '.$activity->getName().' - '] = $lastChoices;

        //$choices = [$activity->getName() => $activityStages];

        //print_r(array_combine($keys,$values));

        $builder->add('activityReport', ChoiceType::class,
            [
                'label' => false,
                'choices' => $choices,
                'expanded' => false,
                'multiple' => false,
                //'choices_as_values' => false,
                'constraints' => [
                    new Assert\NotBlank
                ],
                'attr' => [
                    'style' => 'margin-bottom: 15px; display: block!important',
                ],

                'choice_attr' => function($val,$key,$index){
                    if(strpos($val,'(*)') > 0){
                        return ['disabled' => 'disabled'];
                    } else {
                        return [];
                    }
                }
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        //$resolver->setDefault('data_class',Activity::class);
        $resolver->setRequired('activity');
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');
    }

}
