<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserFixtures
 * @package App\DataFixtures
 */
class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $passwordEncoder;

    /**
     * UserFixtures constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->createAdminUser($manager);
        $this->createRegularUser($manager);
    }

    /**
     * @param ObjectManager $manager
     */
    private function createAdminUser(ObjectManager $manager): void
    {
        $user = new User();

        $user->setEmail('admin@email.com');
        $user->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'admin_password'
        ));

        $manager->persist($user);
        $manager->flush();
    }

    /**
 * @param ObjectManager $manager
 */
    private function createRegularUser(ObjectManager $manager): void
    {
        $user = new User();

        $user->setEmail('user@email.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'user_password'
        ));

        $manager->persist($user);
        $manager->flush();
    }
}
