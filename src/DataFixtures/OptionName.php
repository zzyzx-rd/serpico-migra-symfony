<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OptionName extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $optName1 = new \App\Entity\OptionName();
        $optName1->setType(0)
            ->setName("enabledUserCreatingUser")
            ->setDescription("Enables administrators to define specified users, other than administrators, who have the sole and revokable privilege to add users to the organization.");

        $optName2 = new \App\Entity\OptionName();
        $optName2->setType(0)
            ->setName("mailDeadlineNbDays")
            ->setDescription("Sets the number of days prior to grading deadline where users get notified of it");

        $optName3 = new \App\Entity\OptionName();
        $optName3->setType(0)
            ->setName("enabledSuperiorSubRights")
            ->setDescription("Enables superior(s) to have access to management and results of their subordinates");

        $optName4 = new \App\Entity\OptionName();
        $optName4->setType(0)
            ->setName("enabledSuperiorSettingTargets")
            ->setDescription("Enables superior(s) to set targets to their direct subordinates");

        $optName5 = new \App\Entity\OptionName();
        $optName5->setType(0)
            ->setName("enabledSuperiorModifySubordinate")
            ->setDescription("Enable superior(s) to modify their subordinate info/data");

        $optName6 = new \App\Entity\OptionName();
        $optName6->setType(0)
            ->setName("enabledSuperiorOverviewSubResults")
            ->setDescription("Enable superior(s) to view their subordinate results throughout time");
        $optName7 = new \App\Entity\OptionName();
        $optName7->setType(0)
            ->setName("enabledUserSeeSnapshotPeersResults")
            ->setDescription("Enable users within a team to see their peer snapshot results");

        $optName8 = new \App\Entity\OptionName();
        $optName8->setType(0)
            ->setName("enabledUserSeeSnapshotSupResults")
            ->setDescription("Enable users to view snapshot results of their superior");

        $optName9 = new \App\Entity\OptionName();
        $optName9->setType(0)
            ->setName("enabledUserSeeAllUsers")
            ->setDescription("Enable users to view all firms users in their 'Colleagues & Teams' tab");
        $optName10 = new \App\Entity\OptionName();
        $optName10->setType(0)
            ->setName("enabledCNamesOutsideCGroups")
            ->setDescription("Enables users to see their ranking, based on their previous finished activities in the organization");

        $optName12 = new \App\Entity\OptionName();
        $optName12->setType(0)
            ->setName("activitiesAccessAndResultsView")
            ->setDescription("Depending on user role, defines range of activities accessibility, level of detail, scope of results,  depending or not of his stage ownership");

        $optName11 = new \App\Entity\OptionName();
        $optName11->setType(0)
            ->setName("enabledUserSeeRanking")
            ->setDescription("Enables users to see their ranking, based on their previous finished activities in the organization");

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
    }
}
