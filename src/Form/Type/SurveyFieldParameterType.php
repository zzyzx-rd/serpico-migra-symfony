<?php


namespace App\Form\Type;

use App\Entity\Survey;
use App\Entity\SurveyFieldParameter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SurveyFieldParameterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


                $builder->add('value', TextType::class,
                    [
                        'label' => false,
                        'required' => true,
                    ]);


                    $builder->add('lowerbound', IntegerType::class,
                        [
                            'label' => 'min',
                            'required' => false,
                        ]);
                    $builder->add('upperbound', IntegerType::class,
                        [
                            'label' => 'max',
                            'required' => false,
                        ]);



                }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SurveyFieldParameter::class,
            'edition' => true,
        ]);
    }
}
