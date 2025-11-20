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
        $user = new User();
        $user->setUsername('admin');
        $user->setRoles(['ROLE_ADMIN']);
        $hashedPassword = $this->password_hasher->hashPassword(
            $user,
            'adminpassword'
        );
        $user->setPassword($hashedPassword);
        $manager->persist($user);

        $manager->flush();
    }
}
