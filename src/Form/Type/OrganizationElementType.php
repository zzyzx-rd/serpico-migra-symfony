<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etudiant
 * Date: 07/12/2017
 * Time: 09:35
 */
namespace App\Form\Type;

use Doctrine\ORM\EntityRepository;
use App\Entity\Department;
use App\Entity\Position;
use App\Entity\Title;
use App\Entity\User;
use App\Entity\Weight;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Validator\UniquePerOrganization;

class OrganizationElementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $organization = $options['organization'];
        $elmtType = $options['elmtType'];
        $element = $builder->getData();

        if (!$options['standalone'] && $elmtType == 'department') {

            $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($builder, $organization) {

                /** @var Department|null $data */
                $data = $event->getData();
                $form = $event->getForm();

                $form->add('masterUser', EntityType::class,
                    [
                        'class' => User::class,
                        'choice_label' => 'fullname',
                        'query_builder' => function (EntityRepository $er) use ($data, $organization) {

                            // Data is null when a new form is added, so we needed to find a way to add correct users
                            if ($data != null) {
                                return $er->createQueryBuilder('u')
                                    ->where('u.orgId =' . $data->getOrganization()->getId())
                                    ->andWhere('u.deleted is NULL')
                                    ->orderBy('u.lastname', 'ASC');

                            } else {
                                return $er->createQueryBuilder('u')
                                    ->where('u.orgId =' . $organization->getId())
                                    ->andWhere('u.deleted is NULL')
                                    ->orderBy('u.lastname', 'ASC');
                            }

                        },
                        'attr' => [
                            'style' => 'display: block!important; font-family: Roboto, FontAwesome',
                        ],
                        'label_format' => 'organization_elements.%name%',
                        'required' => false,
                    ]);

            });

        }

        // Field/entry property

        if ($elmtType != 'weight') {

            $builder->add('name', TextType::class,
                [
                    'constraints' => [
                        new Assert\NotBlank([
                            //'message' => 'The stage must have a name'
                        ]),
                        new UniquePerOrganization([
                            'organization' => $organization,
                            'entity' => $elmtType,
                            'element' => $element,
                            'property' => 'name',
                            'message' => 'doublon_' . $elmtType,
                        ]
                        ),
                    ],
                    'label_format' => 'organization_elements.%name%',
                ]);

        } else {

            $builder->add('value', IntegerType::class,
                [
                    'constraints' => [
                        new Assert\NotBlank,
                        new Assert\GreaterThan(0),
                        new UniquePerOrganization([
                            'organization' => $organization,
                            'entity' => $elmtType,
                            'element' => $element,
                            'property' => 'value',
                            'message' => 'doublon_' . $elmtType,
                        ]
                        ),
                    ],
                    'label_format' => 'organization_elements.%name%',
                ]);
        }

        if ($options['standalone']) {
            $builder->add('submit', SubmitType::class, [
                'label_format' => 'create_position.%name%',
                'attr' => [
                    'class' => 'btn element-submit',
                ],
            ]);
        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('usedForUserCreation', false);
        $resolver->setDefault('elmtType', 'department');
        $resolver->setDefault('data_class', function (Options $options) {
            switch ($options['elmtType']) {
                case 'department':
                    return Department::class;
                    break;
                case 'position':
                    return Position::class;
                    break;
                case 'title':
                    return Title::class;
                    break;
                case 'weight':
                    return Weight::class;
                    break;
            }
        });
        $resolver->setDefault('standalone', false);
        $resolver->setDefault('organization', null);
        $resolver->addAllowedTypes('standalone', 'bool');
    }

}
