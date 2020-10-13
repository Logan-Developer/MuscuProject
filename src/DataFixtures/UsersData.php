<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersData extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {

        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {

        $user = new Users();
        $user->setFirstname('Test')
            ->setLastname('USER')
            ->setUsername('testUser')
            ->setEmail('testUser@gmail.com')
            ->setPassword($this->passwordEncoder->encodePassword($user, 'test'));

        $manager->persist($user);

        $reda = new Users();
        $reda->setFirstname('Redacteur')
            ->setLastname('REDACTION')
            ->setUsername('reda')
            ->setEmail('reda@gmail.com')
            ->setPassword($this->passwordEncoder->encodePassword($user, 'reda'))
            ->setRoles(['ROLE_REDACTOR']);

        $manager->persist($reda);

        $coatch = new Users();
        $coatch->setFirstname('Coatch')
            ->setLastname('COATCH')
            ->setUsername('coatch')
            ->setEmail('coatch@gmail.com')
            ->setPassword($this->passwordEncoder->encodePassword($user, 'coatch'))
            ->setRoles(['ROLE_COATCH']);

        $manager->persist($coatch);

        $admin = new Users();
        $admin->setFirstname('Administrateur')
            ->setLastname('ADMINISTRATION')
            ->setUsername('admin')
            ->setEmail('admin@gmail.com')
            ->setPassword($this->passwordEncoder->encodePassword($user, 'admin'))
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        $manager->flush();
    }
}
