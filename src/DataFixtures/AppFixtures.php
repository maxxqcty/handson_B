<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Product;
use App\Entity\Order;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // --- USERS ---
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'adminpassword'));
        $manager->persist($admin);

        $staff = new User();
        $staff->setUsername('staff');
        $staff->setRoles(['ROLE_STAFF']);
        $staff->setPassword($this->passwordHasher->hashPassword($staff, 'staffpassword'));
        $manager->persist($staff);

        // --- PRODUCTS (Water Refilling Station) ---
        $product1 = new Product();
        $product1->setProductName('Purified Water 5 Gallons');
        $product1->setType('Water Product');
        $product1->setPrice('40.00');
        $product1->setUnit('gallons');
        $product1->setStock('150');
        $manager->persist($product1);

        $product2 = new Product();
        $product2->setProductName('Mineral Water 5 Gallons');
        $product2->setType('Water Product');
        $product2->setPrice('45.00');
        $product2->setUnit('gallons');
        $product2->setStock('120');
        $manager->persist($product2);

        $product3 = new Product();
        $product3->setProductName('Alkaline Water 5 Gallons');
        $product3->setType('Water Product');
        $product3->setPrice('50.00');
        $product3->setUnit('gallons');
        $product3->setStock('100');
        $manager->persist($product3);

        $product4 = new Product();
        $product4->setProductName('Empty Gallon Bottle');
        $product4->setType('Container');
        $product4->setPrice('180.00');
        $product4->setUnit('pcs');
        $product4->setStock('200');
        $manager->persist($product4);

        $product5 = new Product();
        $product5->setProductName('Gallon Cap');
        $product5->setType('Accessory');
        $product5->setPrice('5.00');
        $product5->setUnit('pcs');
        $product5->setStock('500');
        $manager->persist($product5);

        // --- ORDERS ---
        $order1 = new Order();
        $order1->setCustomerName('Juan Dela Cruz');
        $order1->setProduct('Purified Water 5 Gallons');
        $order1->setAddress('123 Rizal St, Manila');
        $order1->setTotal('80.00');
        $order1->setItems('2');
        $manager->persist($order1);

        $order2 = new Order();
        $order2->setCustomerName('Maria Clara');
        $order2->setProduct('Mineral Water 5 Gallons');
        $order2->setAddress('456 Mabini Ave, Quezon City');
        $order2->setTotal('90.00');
        $order2->setItems('2');
        $manager->persist($order2);

        $order3 = new Order();
        $order3->setCustomerName('Jose Rizal');
        $order3->setProduct('Empty Gallon Bottle');
        $order3->setAddress('789 Bonifacio Blvd, Cebu');
        $order3->setTotal('360.00');
        $order3->setItems('2');
        $manager->persist($order3);

        $manager->flush();
    }
}
