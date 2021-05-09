<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder ;
    }

    public function load(ObjectManager $manager)
    {

        $role[] = "ROLE_ADMIN";

        $user = new User();

        $hash = $this->encoder->encodePassword($user, 'password');

        $user->setUsername('Admin')
                ->setPassword($hash)
                ->setRoles($role);

        $manager->persist($user);
        

        $manager->flush();
    }
}
