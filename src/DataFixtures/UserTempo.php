<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserTempo extends Fixture
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

        $user = new \App\Entity\UserTempo();
        $user->setRole(3)
            ->setEmail("sjobs@yopmail.com")
            ->setUsername("Steve Jobs")
            ->setPassword($this->encoder->encodePassword($user, "Serpico2019"));
        $manager->persist($user);
        $manager->flush();
    }
}
