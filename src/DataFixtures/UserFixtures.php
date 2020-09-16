<?php

namespace App\DataFixtures;

use App\Entity\Competance;
use App\Entity\Experience;
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
        $user->setFirstname("mehdi");
        $user->setFlastname("radadi");
        $user->setAddress("30 avenue generale de gaulle rosny sous bois 93110");
        $user->setPhone('+3362167428342');
        $user->setEmail('mehdi.radadi@gmail.com');
        $user->setRoles('ROLE_CONSULTANT');

        $formation = new Formation();
        $formation->setName('Diplome ingenieur informatique');
        $formation->setDateObtained(new \DateTime('2013-07-23'));
        $formation->setEstablishment('Ecole nationale d\'ingenieur de sfax');

        $experience1 = new Experience();
        $experience1->setDescription("Exprience 1 developpeur Back end");
        $experience1->setTitle('Developpeur Back end');
        $experience1->setStartDate(new \DateTime('2013-12-01'));

        $experience2 = new Experience();
        $experience2->setDescription("Exprience 2 developpeur Back end");
        $experience2->setTitle('Developpeur Back end');
        $experience2->setStartDate(new \DateTime('2015-12-01'));

        $competance1 = new Competance();
        $competance1->setName('Symfony');

        $competance2 = new Competance();
        $competance2->setName('PHP');

        $user->addCompetance($competance1);
        $user->addCompetance($competance2);
        $user->addExperience($experience1);
        $user->addExperience($experience2);
        $user->addFormation($formation);

        $manager->persist($user);
        $manager->flush();
    }
}
