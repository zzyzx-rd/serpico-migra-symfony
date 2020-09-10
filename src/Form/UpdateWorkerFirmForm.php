<?php
/**
 * Created by IntelliJ IDEA.
 * User: Etudiant
 * Date: 07/12/2017
 * Time: 09:35
 */
namespace App\Form;

use App\Entity\WorkerFirm;
use App\Entity\WorkerFirmSector;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\Type\ClientUserMType;
use App\Form\Type\UserMType;
use App\Form\Type\ActivityNameType;
use Doctrine\Common\Collections\ArrayCollection;

class UpdateWorkerFirmForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
            $app = $options['app'];
            $workerFirm = $options['workerFirm'];
            $em = $app['orm.em'];
            $repoWF = $em->getRepository(WorkerFirm::class);
            $workerFirmSectors = $em->getRepository(WorkerFirmSector::class)->findAll();
            $arraySearch = [];
            for($i=0;$i<10000;$i++){
                $arraySearch[] = $i;
            }

            $workerFirms = new ArrayCollection($repoWF->findById($arraySearch));

            if(array_search($workerFirm->getId(),$arraySearch) > 0){
                $workerFirms->removeElement($workerFirm);
            }
            foreach($workerFirms as $key => $theWorkerFirm){

                    $keys[] = $theWorkerFirm->getId();
                    $values[] = $theWorkerFirm->getName();
            }

            foreach($workerFirmSectors as $key => $workerFirmSector){

                $WFSKeys[] = $workerFirmSector->getId();
                $WFSValues[] = $workerFirmSector->getName();
            }

            $builder->add('commonName', TextType::class,
            [
                'label_format' => "worker_firm_data.%name%",
                'constraints' => [
                    new Assert\NotBlank,
                ],
                'data' => ($workerFirm->getCommonName() == null) ? $workerFirm->getName() : $workerFirm->getCommonName(),
            ])

            ->add('firmParent', TextType::class,
            [
                'label_format' => "worker_firm_data.%name%",
                'required' => false,

            ])

            ->add('mainSector', ChoiceType::class,
            [
                'choices' => array_combine($WFSValues,$WFSKeys),
                'expanded' => false,
                'multiple' => false,
                'attr' => [
                    'style' => 'display: block!important',
                ],
                'constraints' => [
                    new Assert\NotBlank,
                ],
                'label_format' => "worker_firm_data.%name%",
                'required' => true,
                'placeholder' => false,
            ])

            ->add('website', TextType::class,
            [
                'label_format' => "worker_firm_data.%name%",
                'required' => false,

            ])

            ->add('mailPrefix', ChoiceType::class, [
                'choices' => [
                    'prenom.nom' => 1,
                    'pnom' => 2,
                    'p.nom' => 3,
                    'pn' => 4,
                ],
                'expanded' => false,
                'multiple' => false,
                'attr' => [
                    'style' => 'display: block!important',
                ],
                'constraints' => [
                    new Assert\NotBlank,
                ],
                'label_format' => "worker_firm_data.%name%",
                'required' => false,
                'placeholder' => false,
            ])

            ->add('mailSuffix', TextType::class,
            [
                'label_format' => "worker_firm_data.%name%",
                'required' => false,
            ])

            ->add('url', TextType::class,
            [
                'label_format' => "worker_firm_data.%name%",
                'required' => false,
            ])

            ->add('size', ChoiceType::class, [
                'choices' => [
                    '1-10 employés' => 0,
                    '10-50 employés' => 1,
                    '50-200 employés' => 2,
                    '200-500 employés' => 3,
                    '500-1000 employés' => 4,
                    '1000-5000 employés' => 5,
                    '5k-10k employés' => 6,
                    '10k+ employés' => 7,
                ],
                'expanded' => false,
                'multiple' => false,
                'attr' => [
                    'style' => 'display: block!important',
                ],
                'constraints' => [
                    new Assert\NotBlank,
                ],
                'label_format' => "worker_firm_data.%name%",
                'required' => false,
                'placeholder' => false,
            ])

            ->add('creationDate', DateTimeType::class,
            [
                'format' => 'dd/MM/yyyy',
                'widget' => 'single_text',
                'label_format' => 'worker_firm_data.%name%',
                'html5' => false,
                'attr' => ['class' => 'dp-gstart'],
                'constraints' => [
                    new Assert\NotBlank,
                    //new GSDGreaterThanSD
                ]
            ])

            ->add('HQCity', TextType::class,
            [
                'label_format' => "worker_firm_data.%name%",
            ])

            ->add('HQState', TextType::class,
            [
                'label_format' => "worker_firm_data.%name%",
            ])

            ->add('HQCountry', ChoiceType::class,
            [
                'choices' => array_flip([
                    'AF' => 'Afghanistan',
                    'AX' => 'Aland Islands',
                    'AL' => 'Albania',
                    'DZ' => 'Algeria',
                    'AS' => 'American Samoa',
                    'AD' => 'Andorra',
                    'AO' => 'Angola',
                    'AI' => 'Anguilla',
                    'AQ' => 'Antarctica',
                    'AG' => 'Antigua and Barbuda',
                    'AR' => 'Argentina',
                    'AM' => 'Armenia',
                    'AW' => 'Aruba',
                    'AU' => 'Australia',
                    'AT' => 'Austria',
                    'AZ' => 'Azerbaijan',
                    'BS' => 'Bahamas the',
                    'BH' => 'Bahrain',
                    'BD' => 'Bangladesh',
                    'BB' => 'Barbados',
                    'BY' => 'Belarus',
                    'BE' => 'Belgium',
                    'BZ' => 'Belize',
                    'BJ' => 'Benin',
                    'BM' => 'Bermuda',
                    'BT' => 'Bhutan',
                    'BO' => 'Bolivia',
                    'BA' => 'Bosnia and Herzegovina',
                    'BW' => 'Botswana',
                    'BV' => 'Bouvet Island (Bouvetoya)',
                    'BR' => 'Brazil',
                    'IO' => 'British Indian Ocean Territory (Chagos Archipelago)',
                    'VG' => 'British Virgin Islands',
                    'BN' => 'Brunei Darussalam',
                    'BG' => 'Bulgaria',
                    'BF' => 'Burkina Faso',
                    'BI' => 'Burundi',
                    'KH' => 'Cambodia',
                    'CM' => 'Cameroon',
                    'CA' => 'Canada',
                    'CV' => 'Cape Verde',
                    'KY' => 'Cayman Islands',
                    'CF' => 'Central African Republic',
                    'TD' => 'Chad',
                    'CL' => 'Chile',
                    'CN' => 'China',
                    'CX' => 'Christmas Island',
                    'CC' => 'Cocos (Keeling) Islands',
                    'CO' => 'Colombia',
                    'KM' => 'Comoros the',
                    'CD' => 'Congo',
                    'CG' => 'Congo the',
                    'CK' => 'Cook Islands',
                    'CR' => 'Costa Rica',
                    'CI' => 'Cote d\'Ivoire',
                    'HR' => 'Croatia',
                    'CU' => 'Cuba',
                    'CY' => 'Cyprus',
                    'CZ' => 'Czech Republic',
                    'DK' => 'Denmark',
                    'DJ' => 'Djibouti',
                    'DM' => 'Dominica',
                    'DO' => 'Dominican Republic',
                    'EC' => 'Ecuador',
                    'EG' => 'Egypt',
                    'SV' => 'El Salvador',
                    'GQ' => 'Equatorial Guinea',
                    'ER' => 'Eritrea',
                    'EE' => 'Estonia',
                    'ET' => 'Ethiopia',
                    'FO' => 'Faroe Islands',
                    'FK' => 'Falkland Islands (Malvinas)',
                    'FJ' => 'Fiji the Fiji Islands',
                    'FI' => 'Finland',
                    'FR' => 'France, French Republic',
                    'GF' => 'French Guiana',
                    'PF' => 'French Polynesia',
                    'TF' => 'French Southern Territories',
                    'GA' => 'Gabon',
                    'GM' => 'Gambia the',
                    'GE' => 'Georgia',
                    'DE' => 'Germany',
                    'GH' => 'Ghana',
                    'GI' => 'Gibraltar',
                    'GR' => 'Greece',
                    'GL' => 'Greenland',
                    'GD' => 'Grenada',
                    'GP' => 'Guadeloupe',
                    'GU' => 'Guam',
                    'GT' => 'Guatemala',
                    'GG' => 'Guernsey',
                    'GN' => 'Guinea',
                    'GW' => 'Guinea-Bissau',
                    'GY' => 'Guyana',
                    'HT' => 'Haiti',
                    'HM' => 'Heard Island and McDonald Islands',
                    'VA' => 'Holy See (Vatican City State)',
                    'HN' => 'Honduras',
                    'HK' => 'Hong Kong',
                    'HU' => 'Hungary',
                    'IS' => 'Iceland',
                    'IN' => 'India',
                    'ID' => 'Indonesia',
                    'IR' => 'Iran',
                    'IQ' => 'Iraq',
                    'IE' => 'Ireland',
                    'IM' => 'Isle of Man',
                    'IL' => 'Israel',
                    'IT' => 'Italy',
                    'JM' => 'Jamaica',
                    'JP' => 'Japan',
                    'JE' => 'Jersey',
                    'JO' => 'Jordan',
                    'KZ' => 'Kazakhstan',
                    'KE' => 'Kenya',
                    'KI' => 'Kiribati',
                    'KP' => 'Korea',
                    'KR' => 'Korea',
                    'KW' => 'Kuwait',
                    'KG' => 'Kyrgyz Republic',
                    'LA' => 'Lao',
                    'LV' => 'Latvia',
                    'LB' => 'Lebanon',
                    'LS' => 'Lesotho',
                    'LR' => 'Liberia',
                    'LY' => 'Libyan Arab Jamahiriya',
                    'LI' => 'Liechtenstein',
                    'LT' => 'Lithuania',
                    'LU' => 'Luxembourg',
                    'MO' => 'Macao',
                    'MK' => 'Macedonia',
                    'MG' => 'Madagascar',
                    'MW' => 'Malawi',
                    'MY' => 'Malaysia',
                    'MV' => 'Maldives',
                    'ML' => 'Mali',
                    'MT' => 'Malta',
                    'MH' => 'Marshall Islands',
                    'MQ' => 'Martinique',
                    'MR' => 'Mauritania',
                    'MU' => 'Mauritius',
                    'YT' => 'Mayotte',
                    'MX' => 'Mexico',
                    'FM' => 'Micronesia',
                    'MD' => 'Moldova',
                    'MC' => 'Monaco',
                    'MN' => 'Mongolia',
                    'ME' => 'Montenegro',
                    'MS' => 'Montserrat',
                    'MA' => 'Morocco',
                    'MZ' => 'Mozambique',
                    'MM' => 'Myanmar',
                    'NA' => 'Namibia',
                    'NR' => 'Nauru',
                    'NP' => 'Nepal',
                    'AN' => 'Netherlands Antilles',
                    'NL' => 'Netherlands the',
                    'NC' => 'New Caledonia',
                    'NZ' => 'New Zealand',
                    'NI' => 'Nicaragua',
                    'NE' => 'Niger',
                    'NG' => 'Nigeria',
                    'NU' => 'Niue',
                    'NF' => 'Norfolk Island',
                    'MP' => 'Northern Mariana Islands',
                    'NO' => 'Norway',
                    'OM' => 'Oman',
                    'PK' => 'Pakistan',
                    'PW' => 'Palau',
                    'PS' => 'Palestinian Territory',
                    'PA' => 'Panama',
                    'PG' => 'Papua New Guinea',
                    'PY' => 'Paraguay',
                    'PE' => 'Peru',
                    'PH' => 'Philippines',
                    'PN' => 'Pitcairn Islands',
                    'PL' => 'Poland',
                    'PT' => 'Portugal, Portuguese Republic',
                    'PR' => 'Puerto Rico',
                    'QA' => 'Qatar',
                    'RE' => 'Reunion',
                    'RO' => 'Romania',
                    'RU' => 'Russian Federation',
                    'RW' => 'Rwanda',
                    'BL' => 'Saint Barthelemy',
                    'SH' => 'Saint Helena',
                    'KN' => 'Saint Kitts and Nevis',
                    'LC' => 'Saint Lucia',
                    'MF' => 'Saint Martin',
                    'PM' => 'Saint Pierre and Miquelon',
                    'VC' => 'Saint Vincent and the Grenadines',
                    'WS' => 'Samoa',
                    'SM' => 'San Marino',
                    'ST' => 'Sao Tome and Principe',
                    'SA' => 'Saudi Arabia',
                    'SN' => 'Senegal',
                    'RS' => 'Serbia',
                    'SC' => 'Seychelles',
                    'SL' => 'Sierra Leone',
                    'SG' => 'Singapore',
                    'SK' => 'Slovakia (Slovak Republic)',
                    'SI' => 'Slovenia',
                    'SB' => 'Solomon Islands',
                    'SO' => 'Somalia, Somali Republic',
                    'ZA' => 'South Africa',
                    'GS' => 'South Georgia and the South Sandwich Islands',
                    'ES' => 'Spain',
                    'LK' => 'Sri Lanka',
                    'SD' => 'Sudan',
                    'SR' => 'Suriname',
                    'SJ' => 'Svalbard & Jan Mayen Islands',
                    'SZ' => 'Swaziland',
                    'SE' => 'Sweden',
                    'CH' => 'Switzerland, Swiss Confederation',
                    'SY' => 'Syrian Arab Republic',
                    'TW' => 'Taiwan',
                    'TJ' => 'Tajikistan',
                    'TZ' => 'Tanzania',
                    'TH' => 'Thailand',
                    'TL' => 'Timor-Leste',
                    'TG' => 'Togo',
                    'TK' => 'Tokelau',
                    'TO' => 'Tonga',
                    'TT' => 'Trinidad and Tobago',
                    'TN' => 'Tunisia',
                    'TR' => 'Turkey',
                    'TM' => 'Turkmenistan',
                    'TC' => 'Turks and Caicos Islands',
                    'TV' => 'Tuvalu',
                    'UG' => 'Uganda',
                    'UA' => 'Ukraine',
                    'AE' => 'United Arab Emirates',
                    'GB' => 'United Kingdom',
                    'US' => 'United States of America',
                    'UM' => 'United States Minor Outlying Islands',
                    'VI' => 'United States Virgin Islands',
                    'UY' => 'Uruguay, Eastern Republic of',
                    'UZ' => 'Uzbekistan',
                    'VU' => 'Vanuatu',
                    'VE' => 'Venezuela',
                    'VN' => 'Vietnam',
                    'WF' => 'Wallis and Futuna',
                    'EH' => 'Western Sahara',
                    'YE' => 'Yemen',
                    'ZM' => 'Zambia',
                    'ZW' => 'Zimbabwe',
                ]),
                'attr' => [
                    'style' => 'display: block!important',
                ],
                'constraints' => [
                    new Assert\NotBlank,
                ],
                'label_format' => "worker_firm_data.%name%",
                'required' => true,
                'placeholder' => false,
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
        $resolver->setDefault('data_class',WorkerFirm::class);
        $resolver->setDefault('standalone', false);
        $resolver->addAllowedTypes('standalone', 'bool');
        $resolver->setDefault('organization', null);
        $resolver->setRequired('workerFirm');

    }

}
