<?php

namespace App\DataFixtures;

use App\Entity\Formation;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
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
        $user->setUsername('mradadi');
        $user->setPassword($this->encoder->encodePassword($user, 'demo'));
        $formation = new Formation();
        $formation->setName('Diplome ingenieur');
        $formation->setDateObtained(new \DateTime('2013-07-23'));
        $formation->setEstablishment('Ecole nationale d\'ingenieur de sfax');
        $formation->setUser($user);
        $manager->persist($formation);
        $manager->persist($user);
        $manager->flush();
    }
}
