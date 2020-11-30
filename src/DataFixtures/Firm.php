<?php

namespace App\DataFixtures;

use App\Entity\CriterionGroup;
use App\Entity\CriterionName;
use App\Entity\Department;
use App\Entity\Icon;
use App\Entity\OptionName;
use App\Entity\Organization;
use App\Entity\OrganizationUserOption;
use App\Entity\User;
use App\Entity\UserGlobal;
use App\Entity\UserMaster;
use App\Entity\Weight;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class Firm extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {

        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {

        // Default icons (only 3)
         $icon1 = (new Icon)->setType('fa')->setName('flag-checkered')->setUnicode('f11e');
         $icon2 = (new Icon)->setType('fa')->setName('gem')->setUnicode('f3a5');
         $icon3 = (new Icon)->setType('fa')->setName('hourglass-end')->setUnicode('f253');

         $manager->persist($icon1);
         $manager->persist($icon2);
         $manager->persist($icon3);


         // Defining some criterion names (only 6)

         $crtName1 = (new CriterionName)->setType(1)
             ->setName("Python")
             ->setIcon($icon1);             
 
         $crtName2 = (new CriterionName)->setType(1)
             ->setName("Macro Excel")
             ->setIcon($icon2);     
 
         $crtName3 = (new CriterionName)->setType(1)
             ->setName("Gestion de projet")
             ->setIcon($icon3);

         $crtName4 = (new CriterionName)->setType(2)
             ->setName("Ponctualité")
             ->setIcon($icon1);     
             
         $crtName5 = (new CriterionName)->setType(2)
             ->setName("Confiance en soi")
             ->setIcon($icon2);     
 
         $crtName6 = (new CriterionName)->setType(2)
             ->setName("Travail en équipe")
             ->setIcon($icon3);     


        // Defining common option names shared by all organizations

        $optName1 = new OptionName;
        $optName1->setType(0)
            ->setName("enabledUserCreatingUser")
            ->setDescription("Enables administrators to define specified users, other than administrators, who have the sole and revokable privilege to add users to the organization.");

        $optName2 = new OptionName;
        $optName2->setType(0)
            ->setName("mailDeadlineNbDays")
            ->setDescription("Sets the number of days prior to grading deadline where users get notified of it");

        $optName3 = new OptionName;
        $optName3->setType(0)
            ->setName("enabledSuperiorSubRights")
            ->setDescription("Enables superior(s) to have access to management and results of their subordinates");

        $optName4 = new OptionName;
        $optName4->setType(0)
            ->setName("enabledSuperiorSettingTargets")
            ->setDescription("Enables superior(s) to set targets to their direct subordinates");

        $optName5 = new OptionName;
        $optName5->setType(0)
            ->setName("enabledSuperiorModifySubordinate")
            ->setDescription("Enable superior(s) to modify their subordinate info/data");

        $optName6 = new OptionName;
        $optName6->setType(0)
            ->setName("enabledSuperiorOverviewSubResults")
            ->setDescription("Enable superior(s) to view their subordinate results throughout time");
        $optName7 = new OptionName;
        $optName7->setType(0)
            ->setName("enabledUserSeeSnapshotPeersResults")
            ->setDescription("Enable users within a team to see their peer snapshot results");

        $optName8 = new OptionName;
        $optName8->setType(0)
            ->setName("enabledUserSeeSnapshotSupResults")
            ->setDescription("Enable users to view snapshot results of their superior");

        $optName9 = new OptionName;
        $optName9->setType(0)
            ->setName("enabledUserSeeAllUsers")
            ->setDescription("Enable users to view all firms users in their 'Colleagues & Teams' tab");
        $optName10 = new OptionName;
        $optName10->setType(0)
            ->setName("enabledCNamesOutsideCGroups")
            ->setDescription("Enables users to see their ranking, based on their previous finished activities in the organization");

        $optName12 = new OptionName;
        $optName12->setType(0)
            ->setName("activitiesAccessAndResultsView")
            ->setDescription("Depending on user role, defines range of activities accessibility, level of detail, scope of results,  depending or not of his stage ownership");

        $optName11 = new OptionName;
        $optName11->setType(0)
            ->setName("enabledUserSeeRanking")
            ->setDescription("Enables users to see their ranking, based on their previous finished activities in the organization");
        
        $manager->persist($crtName1);
        $manager->persist($crtName2);
        $manager->persist($crtName3);
        $manager->persist($crtName4);
        $manager->persist($crtName5);
        $manager->persist($crtName6);
            
        $manager->persist($optName1);
        $manager->persist($optName2);
        $manager->persist($optName3);
        $manager->persist($optName4);
        $manager->persist($optName5);
        $manager->persist($optName6);
        $manager->persist($optName7);
        $manager->persist($optName8);
        $manager->persist($optName9);
        $manager->persist($optName10);
        $manager->persist($optName11);
        $manager->persist($optName12);
        $manager->flush();

        // Creating first organization

        $masterUser = new User();
        $masterUser->setRole(3)
            ->setEmail("gchatelain@yopmail.com")
            ->setUsername("Guillaume Chatelain")
            ->setFirstname("Guillaume")
            ->setLastname("Chatelain")
            ->setPassword($this->encoder->encodePassword($masterUser, "Serpico2019"))
            ->setRole(1);
        $manager->persist($masterUser);

        $userMaster = new UserMaster();
        $userMaster->setUser($masterUser);

        $serpico = new Organization();
        $serpico->setType('f')
            ->setIsClient(1)
            ->setCommname("Serpico")
            ->setLegalname("Serpico")
            ->addUserMaster($userMaster);
        
        $userGlobal = new UserGlobal();
        $userGlobal->setUsername("Guillaume Chatelain")
        ->addUserAccount($masterUser);
        $manager->persist($userGlobal);

         // Settling default options
        /** @var OptionName[] */
        $options = $manager->getRepository(OptionName::class)->findAll();
        
        foreach ($options as $option) {

            $optionValid = (new OrganizationUserOption)
            ->setOName($option);

            // We set nb of days for reminding emails, very important otherwise if unset, if people create activities, can make system bug.
            //  => Whenever someone logs in, this person triggers reminder mails to every person in every organization, organization thus should have this parameter date set.
            if($option->getName() == 'mailDeadlineNbDays'){
                $optionValid->setOptionFValue(2);
            }
            //$em->persist($optionValid);

            // At least 3 options should exist for a new firm for activity & access results
            if($option->getName() == 'activitiesAccessAndResultsView'){

                // Visibility and access options has many options :
                // * Scope (opt_bool_value in DB, optionTrue property) : defines whether user sees his own results (0), or all participant results (1)
                // * Activities access (opt_int_value, optionIValue property) : defines whether user can access all organisation acts (1), his department activities (2) or his own activities (3)
                // * Status access (opt_int_value_2, optionSecondaryIValue property) : defines whether user can access computed results (2), or released results (3)
                // * Detail (opt_float_value, optionFValue property) : defines whether user accesses averaged/consolidated results (0), or detailed results (1)
                // * Results Participation Condition (opt_string_value, optionSValue property) : defines whether user accesses activity results without condition ('none'), if he is activity owner ('owner'), or if he is participating ('participant')

                $optionAdmin = $optionValid;
                $optionAdmin->setRole(1)->setOptionTrue(true)->setOptionIValue(1)->setOptionSecondaryIValue(2)->setOptionFValue(1)->setOptionSValue('none');
                $serpico->addOption($optionAdmin);

                $optionAM = (new OrganizationUserOption)
                    ->setOName($option)
                    ->setRole(2)
                    ->setOptionTrue(true)
                    ->setOptionIValue(2)
                    ->setOptionSecondaryIValue(2)
                    ->setOptionFValue(0)
                    ->setOptionSValue('owner');
                $serpico->addOption($optionAM);

                $optionC = (new OrganizationUserOption)
                    ->setOName($option)
                    ->setRole(3)
                    ->setOptionTrue(false)
                    ->setOptionIValue(3)
                    ->setOptionSecondaryIValue(3)
                    ->setOptionFValue(0)
                    ->setOptionSValue('participant');
                $serpico->addOption($optionC);
                //$em->persist($optionC);
            } else {
                $serpico->addOption($optionValid);
            }
        }

        // Settling default criterion names
        $repoCN = $manager->getRepository(CriterionName::class);
        $criterionGroups = [
            1 => new CriterionGroup('Hard skills'),
            2 => new CriterionGroup('Soft skills')
        ];

        foreach ($criterionGroups as $cg) {
            $serpico->addCriterionGroup($cg);
        }

        /**
         * @var CriterionName[]
         */
        $defaultCriteria = $repoCN->findBy(['organization' => null]);
        foreach ($defaultCriteria as $defaultCriterion) {
            $criterion = clone $defaultCriterion;
            // 1: hard skill
            // 2: soft skill
            $type = $criterion->getType();
            $cg = $criterionGroups[$type];
            $criterion
                //->setOrganization($organization)
                ->setCriterionGroup($cg);

            $cg->addCriterion($criterion);
            $serpico->addCriterionName($criterion);
            //$em->persist($criterion);
        }
        
        /** @var Weight */
        $weight = new Weight();
        $weight->setValue(100);

        $jobs = new User();
        $jobs->setEmail("sjobs@yopmail.com")
            ->setUsername("Steve Jobs")
            ->setFirstname("Steve")
            ->setLastname("Jobs")
            ->setRole(1)
            ->setPassword($this->encoder->encodePassword($jobs, "Serpico2019"));
        
        $userGlobalJobs = new UserGlobal();
        $userGlobalJobs->setUsername("Steve Jobs")
            ->addUser($masterUser);
        $manager->persist($userGlobalJobs);

        $treicher = new User();
        $treicher->setEmail("treicher@yopmail.com")
            ->setUsername("Thomas Reicher")
            ->setFirstname("Thomas")
            ->setLastname("Reicher")
            ->setRole(3)
            ->setPassword($this->encoder->encodePassword($treicher, "Serpico2019"));

        $userGlobalReicher = new UserGlobal();
        $userGlobalReicher->setUsername("Thomas Reicher")
            ->addUser($masterUser);
        $manager->persist($userGlobalReicher);

        $synth = new User();
        $synth//->setUsername("ZZ - ".$serpico->getCommname())
            ->setFirstname('ZZ')
            ->setLastname($serpico->getCommname())
            ->setSynthetic(true);

        $departement = new Department();
        $departement->addUserMaster($userMaster)
            ->setName("Development");

        $weight->addUser($masterUser)->addUser($treicher)->addUser($jobs);
        $serpico->addUser($masterUser)->addUser($treicher)->addUser($jobs);
        $serpico->addWeight($weight);
        $serpico->addDepartment($departement);
        $manager->persist($serpico);
        $manager->flush();
    }
}
