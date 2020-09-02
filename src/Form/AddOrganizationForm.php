<?php
namespace App\Form;

use App\Entity\Organization;
use App\Entity\Position;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Validator\UniquePerOrganization;

class AddOrganizationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var int */
        $orgId = $options['orgId'];
        /** @var bool */
        $standalone = $options['standalone'];
        /** @var bool */
        $toValidate = $options['toValidate'];
        /** @var bool */
        $isFromClient = isset($options['isFromClient']);

        $em = $options['em'];
        $repoO = $em->getRepository(Organization::class);
        $repoU = $em->getRepository(User::class);
        $repoP = $em->getRepository(Position::class);

        /** @var Organization|null */
        $organization = $repoO->find($orgId);

        $builder->add('commname', TextType::class,
            [
                'label_format' => $options['isFromClient'] ? 'create_organization.your_firm' : 'create_organization.%name%',
                'constraints' => [
                    new Assert\NotBlank
                ],
                'data' => $organization ? $organization->getCommname() : null,
                'attr' => [
                    'disabled' => $toValidate
                ]
            ]
        );

        // If organization is being created, we let root define the new master user; otherwise, has to be part of the organization
        if (!$organization || ($organization && $toValidate)) {
            $orgMasterUserFirstName = null;
            $orgMasterUserLastName = null;
            $orgMasterUserEmail = null;
            $orgMasterUserPositionName = null;
            $orgMasterUserDepartmentName = null;

            if ($organization && $toValidate) {
                $orgMasterUserId = $organization->getMasterUserId();
                /** @var User */
                $orgMasterUser = $repoU->find($orgMasterUserId);
                $orgMasterUserFirstName = $orgMasterUser->getFirstname();
                $orgMasterUserLastName = $orgMasterUser->getLastname();
                $orgMasterUserEmail = $orgMasterUser->getEmail();
                $orgMasterUserPosition = $orgMasterUser->getPosition();
                $orgMasterUserDepartment = $orgMasterUser->getDepartment();
                $orgMasterUserPositionName = $orgMasterUserPosition
                                           ? $orgMasterUserPosition->getName()
                                           : null;
                $orgMasterUserDepartmentName = $orgMasterUserDepartment
                                           ? $orgMasterUserDepartment->getName()
                                           : null;
            }

            $builder
            ->add('firstname', TextType::class,
                [
                    'label_format' => 'create_organization.%name%',
                    'constraints' => $isFromClient ? [
                        new Assert\NotBlank
                    ] : null,
                    'required' => false,
                    'data' => $orgMasterUserFirstName,
                    'attr' => [
                        'disabled' => $toValidate
                    ]
                ]
            )
            ->add('lastname', TextType::class,
                [
                    'label_format' => 'create_organization.%name%',
                    'constraints' => $isFromClient ? [
                         new Assert\NotBlank
                    ] : null,
                    'required' => false,
                    'data' => $orgMasterUserLastName,
                    'attr' => [
                        'disabled' => $toValidate
                    ]
                ]
            )
            ->add('email', TextType::class,
                [
                    'label_format' => 'create_organization.%name%',
                    'constraints' => $isFromClient ? [
                        new Assert\NotBlank,
                        new Assert\Regex([
                            'pattern' => "/^(?:[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])*$/",
                            'message' => '*Email is invalid, please enter a valid email adress in this field (example : some@thing.com).'
                        ]),
                        new UniquePerOrganization([
                            'organization' => null,
                            'entity' => 'user',
                            'element' => $builder->getData(),
                            'property' => 'email',
                            'message' => 'create_users.doublon_email_user'
                        ]),
                    ] : null,
                    'required' => false,
                    'data' => $orgMasterUserEmail,
                    'attr' => [
                        'disabled' => $toValidate
                    ]
                ]
            );
            if(!$options['isFromClient']){

                $builder->add('position', TextType::class,
                    [
                        'label_format' => 'create_organization.%name%',
                        'constraints' => $isFromClient ? [
                            new Assert\NotBlank
                        ] : null,
                        'required' => false,
                        'data' => $orgMasterUserPositionName,
                        'attr' => [
                            'disabled' => $toValidate
                        ]
                    ]
                )
                ->add('department', TextType::class,
                    [
                        'label_format' => 'create_organization.%name%',
                        'constraints' => $isFromClient ? [
                            new Assert\NotBlank
                        ] : null,
                        'required' => false,
                        'data' => $orgMasterUserDepartmentName,
                        'attr' => [
                            'disabled' => $toValidate
                        ]
                    ]
                )
                ->add('type', ChoiceType::class, [

                    'choices' => [
                        'create_organization.type.institution' => 'P',
                        'create_organization.type.client' => 'F',
                        'create_organization.type.team_project' => 'T',
                        'create_organization.type.individual' => 'I',
                    ],
                    'expanded' => false,
                    'multiple' => false,
                    'attr' => [
                        'class' => 'browser-default'
                    ],
                    'constraints' => [
                        new Assert\NotBlank
                    ],
                    'label' => 'create_organization.type.label',
                    'data' => $toValidate ? $organization->getType() : 'P',
                    'required' => true,
                    'placeholder' => false
                ]);
            }
        } else {
            // Get users, build choices array
            /** @var User[] */
            $users = $repoU->findByOrgId($orgId);
            $masterUserChoices = [];
            foreach ($users as $user) {
                $masterUserChoices[$user->getId()] = $user->getFullName();
            }

            $builder->add('masterUser', ChoiceType::class,
                [
                    'label_format' => 'create_organization.%name%',
                    'choices' => $masterUserChoices,
                    'expanded' => false,
                    'multiple' => false,
                    'constraints' => [
                        new Assert\NotBlank
                    ],
                    'attr' => [
                        'class' => 'browser-default'
                    ]
                ]
            );
        }

        if (!$toValidate) {
            if ($standalone) {
                $builder->add('submit', SubmitType::class,
                    [
                        'label' => $options['isFromClient'] ? 'create_organization.letz_go' : ($organization ? 'create_organization.update_btn' : 'create_organization.add_btn'),
                        'attr' => [
                            'class'=> 'btn btn-large'
                        ]
                    ]
                );
            }
        } else {
            $builder
            ->add('reject', SubmitType::class,
                [
                    'label_format' => 'create_organization.%name%',
                    'attr' => [
                        'class' => 'red btn btn-large'
                    ]
                ]
            )
            ->add('validate', SubmitType::class,
                [
                    'label_format' => 'create_organization.%name%',
                    'attr' => [
                        'class' => 'btn btn-large'
                    ]
                ]
            );
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        global $app;

        $resolver
        ->setDefaults([
            'standalone' => false,
            'toValidate' => false,
            'request' => false,
            'em' => null,
            'isFromClient' => false,
        ])
        ->setRequired([
            'orgId'
        ])
        ->addAllowedTypes('standalone', 'bool')
        ->addAllowedTypes('request', 'bool')
        ->addAllowedTypes('toValidate', 'bool');
    }
}
