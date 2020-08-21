<?php
/**
 * Created by IntelliJ IDEA.
 * User: Faventyne
 * Date: 28/12/2017
 * Time: 15:25
 */

namespace App\Form\Type;

use App\Entity\ActivityUser;
use App\Entity\Grade;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use App\Entity\Stage;
use App\Validator\ForcedComment;

class GradeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function ($event) {
            $form = $event->getForm(); // The FormBuilder
            $grade = $event->getData(); // The Form Object
            // Do whatever you want here!

            $form->add('comment', TextareaType::class,
            [
                'label_format' => 'criteria.criterion.%name%',
                'required' => false,
                'attr' => [
                    'style' => 'min-height:200px',
                ],
                'constraints' => [
                    new ForcedComment([
                        'criterion' => $grade->getCriterion()
                        ]
                    )
                ]

            ]);
            
            if($grade->getCriterion()->getType() != 2){

                $form->add('value', NumberType::class,
                    [
                        'constraints' => [
                        ],
                        'attr' => [
                            'min' => 0,
                            'max' => 10,
                            'class' => 'grade-input',
                        ],
                        'required' => false,
                    ]);
            }

        });

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class',Grade::class);
    }

}