<?php


namespace App\Form\Type;


use Controller\MasterController;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use App\Form\Type\SurveyFieldType;
use App\Entity\SurveyField;
use App\Form\Type\AnswerResponseType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use App\Form\Type\ProcessType;
use App\Entity\Activity;
use App\Entity\Survey;
use App\Entity\Answer;
use App\Entity\TemplateActivity;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType as CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Twig\Extension\StagingExtension;
use App\Form\Type\StageCriterionType;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotNull;
use Validator\UniquePerOrganization;
use Symfony\Component\Validator\Constraints as Assert;




class AnswerType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) use ($builder) {
                $form = $event->getForm();
                $child = $event->getData();
                $currentUser = MasterController::getAuthorizedUser();

                if ($child instanceof Answer) {
                    $SF = $child->getField();
                    $value = [];

                    for ($i = 0; $i < count($SF->getParameters()); $i++) {
                        array_push($value, $SF->getParameters()[$i]->getValue());
                    }

                    
                    switch ($SF->getType()) {
                        case 'ST':
                            if ($SF->isMandatory()) {
                                $form->add('desc', TextType::class, [
                                    'label' => $SF->getTitle(),
                                    'required' => false,
                                    'constraints' => [
                                        new NotBlank(),
                                    ]
                                ]);

                            } else {
                                $form->add('desc', TextType::class, [
                                    'label' =>  $SF->getTitle(),
                                    'required' => false,
                                ]);
                            }
                            break;

                        case 'LT':
                            if ($SF->isMandatory()) {
                                $form->add('desc', TextareaType::class, [
                                    'label' =>  $SF->getTitle(),
                                    'required' => false,
                                    'constraints' => [
                                        new NotBlank(),
                                    ]
                                ]);
                            } else {
                                $form->add('desc', TextareaType::class, [
                                    'label' =>  $SF->getTitle(),
                                    'required' => false,
                                ]);
                            }
                            break;

                        case 'UC':



                                $form->add('desc', CheckboxType::class, [
                                    'label' =>  $SF->getTitle(),
                                    'required' => false,



                                    'attr' => [

                                        'class' => 'uniqueChoice',
                                    ]
                                ]);

                            break;

                        case 'SC':
                            if ($SF->isMandatory()) {
                                $form->add('desc', ChoiceType::class, [
                                    'choices' => $value,
                                    'choice_label' => function ($value) {
                                        return $value ? strtoupper($value) : '';
                                    },
                                    'expanded' => true,
                                    'multiple' => false,
                                    'label' =>  $SF->getTitle(),
                                    'required' => false,
                                    'placeholder' => false,

                                ]);
                            } else {
                                $form->add('desc', ChoiceType::class, [
                                    'choices' => $value,
                                    'choice_label' => function ($value) {
                                        return $value ? strtoupper($value) : '';
                                    },
                                    'expanded' => true,
                                    'multiple' => false,
                                    'label' =>  $SF->getTitle(),
                                    'required' => false,
                                    'placeholder' => false
                                ]);
                            }
                            break;

                        case 'MC':
                            if ($SF->isMandatory()) {
                                $form->add('desc', ChoiceType::class, [
                                    'choices' => $value,
                                    'choice_label' => function ($value) {
                                        return $value ? strtoupper($value) : '';
                                    },
                                    'multiple' => true,
                                    'expanded' => true,
                                    'label' =>  $SF->getTitle(),
                                    'required' => false,


                                ]);
                            } else {
                                $form->add('desc', ChoiceType::class, [
                                    'choices' => $value,
                                    'choice_label' => function ($value) {
                                        return $value ? strtoupper($value) : '';
                                    },
                                    'multiple' => true,
                                    'expanded' => true,
                                    'label' =>  $SF->getTitle(),
                                    'required' => false
                                ]);
                            }
                            break;

                        case 'LS':

                            $form->add('desc', RangeType::class, [
                                'label' =>  $SF->getTitle(),
                                'required' => false,
                                'attr' => [
                                    'min' => $SF->getLowerbound(),
                                    'max' => $SF->getUpperbound(),
                                    'class' => 'active'
                                ]
                            ]);
                            break;
                        default:
                            throw new \Exception('Unexpected value');
                    }
                }
            }

        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Answer::class,
        ]);
    }
}
