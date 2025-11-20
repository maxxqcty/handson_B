<?php

namespace App\DataFixtures;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
  private UserPasswordHasherInterface $password_hasher;
  public function __construct(UserPasswordHasherInterface  $password_hasher)
  {
    $this->password_hasher = $password_hasher;
  }
    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $hashedPassword = $this->password_hasher->hashPassword(
            $admin,
            'adminpassword'
        );
        $admin->setPassword($hashedPassword);
        $manager->persist($admin);

        $user= new User();
        $user->setUsername('user');
        $user->setRoles(['ROLE_USER']);
        $hashedPassword = $this->password_hasher->hashPassword(
            $user,
            'userpassword'
        );
        $user->setPassword($hashedPassword);
        $manager->persist($user);


        $manager->flush();
    }
}
