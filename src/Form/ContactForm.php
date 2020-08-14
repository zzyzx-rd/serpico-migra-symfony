<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etudiant
 * Date: 07/12/2017
 * Time: 09:35
 */
namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints as Assert;
use Validator\MTGreaterThanToday;
use Controller\MasterController;

class ContactForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $regex="/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/";

        $builder->add('fullName', TextType::class,
            [
                'constraints' => [
                    new Assert\NotBlank,
                ],
                'label_format' => 'landing.contact.%name%',
                'required' => true,
            ])
            ->add('meetingDate', DateTimeType::class,
            [
                'format' => 'dd/MM/yyyy',
                'widget' => 'single_text',
                'label_format' => 'landing.contact.%name%',
                'html5' => false,
                'attr' => ['class' => 'dp-mdate'],
                //'data' => $options['enddate'],
                'constraints' => [
                    new Assert\NotBlank,
                    new MTGreaterThanToday,
                ]
            ])
            ->add('meetingTime', DateTimeType::class,
            [
                'format' => 'HH:mm',
                'widget' => 'single_text',
                'label_format' => 'landing.contact.%name%',
                'html5' => false,
                'attr' => ['class' => 'dp-mtime'],
                //'data' => $options['enddate'],
                'constraints' => [
                    new Assert\NotBlank,
                ]
            ])
            ->add('mail', TextType::class, [
                'label_format' => 'landing.contact.%name%',
                'constraints' => [
                    new Assert\NotBlank,
                    new Assert\NotBlank,
                    new Assert\Regex([
                        // 'pattern' => "/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/",
                        'pattern' => $regex,
                        'message' => "Email is invalid, please enter a valid email adress in this field (example : some@thing.com)",
                    ]),
                ],
                'required' => true,
            ])
            ->add('company', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank,
                ],
                'label_format' => 'landing.contact.%name%',
                'required' => true,

            ])
            ->add('address', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank,
                ],
                'label_format' => 'landing.contact.%name%',
                'required' => true,
            ])
            ->add('zipcode', IntegerType::class, [
                'constraints' => [
                    new Assert\NotBlank,
                ],
                'label_format' => 'landing.contact.%name%',
                'required' => true,

            ])
            ->add('city', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank,
                ],
                'label_format' => 'landing.contact.%name%',
                'required' => true,
            ])
            ->add('country', ChoiceType::class, [
                'choices' => [
                    'DE' => 'DE',
                    'FR' => 'FR',
                    'LU' => 'LU',
                    'SP' => 'SP',
                    'PT' => 'PT',
                    'GB' => 'GB',
                ],
                'data' => 'LU',
                'constraints' => [
                    new Assert\NotBlank,
                ],
                'label_format' => 'landing.contact.%name%',
                'required' => true,
            ])
            ->add('message', TextareaType::class, [
                'label_format' => 'landing.contact.%name%',
                'required' => false,
                'attr' => [
                    'style' => 'min-height:100px',
                ]

            ]);


        //TODO : afficher le total déjà distribué dans le label
        //TODO : créer une contrainte de non dépassement en cas de définition relatives

        if ($options['standalone']){
            $builder->add('submit', SubmitType::class,[
                'label_format' => 'landing.contact.%name%',
                'attr' => [
                    'class' => 'btn waves-effect waves-light teal lighten-1 contact-submit',
                ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class',Contact::class);
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');

    }

}
