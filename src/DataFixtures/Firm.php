<?php

namespace App\DataFixtures;

use App\Entity\Department;
use App\Entity\Organization;
use App\Entity\User;
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
        $user = new User();
        $user->setRole(3)
            ->setEmail("gchatelain@yopmail.com")
            ->setUsername("Guillaume Chatelain")
            ->setFirstname("Guillaume")
            ->setLastname("Chatelain")
            ->setPassword($this->encoder->encodePassword($user, "Serpico2019"))
            ->setRole(1);
        $manager->persist($user);

        $serpico = new Organization();
        $serpico->setType('f')
            ->setIsClient(1)
            ->setCommname("Serpico")
            ->setLegalname("Serpico ")
            ->setMasterUser($user);
        $manager->persist($serpico);
        $manager->flush();

        $user->setOrganization($serpico);

        $user = new User();
        $user->setEmail("sjobs@yopmail.com")
            ->setUsername("Steve Jobs")
            ->setFirstname("Steve")
            ->setLastname("Jobs")
            ->setRole(1)
            ->setOrganization($serpico)
            ->setPassword($this->encoder->encodePassword($user, "Serpico2019"));

        $gdbdg = new User();
        $gdbdg->setEmail("gdbdg@yopmail.com")
            ->setUsername("Guillaume dBdG")
            ->setFirstname("Guillaume")
            ->setLastname("dBdG")
            ->setRole(3)
            ->setOrganization($serpico)
            ->setPassword($this->encoder->encodePassword($user, "Serpico2019"));

        $manager->persist($user);
        $manager->persist($gdbdg);
        $manager->flush();

        $departement = new Department();
        $departement->setOrganization($serpico)
            ->setMasterUser($user)
            ->setName("developement");
    }
}
