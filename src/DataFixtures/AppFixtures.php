<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Deceased;
use App\Entity\BurialRecord;
use App\Entity\BurialPlot;
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
        $usersData = [
            ['username' => 'admin', 'password' => 'adminpassword', 'roles' => ['ROLE_ADMIN']],
            ['username' => 'staff', 'password' => 'staffpassword', 'roles' => ['ROLE_STAFF']],
        ];

        foreach ($usersData as $userData) {
            $user = new User();
            $user->setUsername($userData['username']);
            $user->setRoles($userData['roles']);
            $user->setPassword($this->passwordHasher->hashPassword($user, $userData['password']));
            $manager->persist($user);
        }

        // --- BURIAL DATA ---
        $deceasedData = [
            [
                'first_name' => 'John', 'last_name' => 'Doe',
                'dob' => '1945-06-15', 'dod' => '2022-01-12',
                'gender' => 'Male', 'cause' => 'Natural Causes', 'notes' => 'Beloved father'
            ],
            [
                'first_name' => 'Mary', 'last_name' => 'Smith',
                'dob' => '1950-09-21', 'dod' => '2021-12-05',
                'gender' => 'Female', 'cause' => 'Heart Attack', 'notes' => 'Loved by all'
            ],
            [
                'first_name' => 'Robert', 'last_name' => 'Johnson',
                'dob' => '1938-03-11', 'dod' => '2020-07-20',
                'gender' => 'Male', 'cause' => 'Cancer', 'notes' => 'Retired teacher'
            ],
            [
                'first_name' => 'Linda', 'last_name' => 'Brown',
                'dob' => '1942-11-30', 'dod' => '2019-08-14',
                'gender' => 'Female', 'cause' => 'Stroke', 'notes' => 'Active in community'
            ],
            [
                'first_name' => 'Michael', 'last_name' => 'Davis',
                'dob' => '1955-05-04', 'dod' => '2023-03-22',
                'gender' => 'Male', 'cause' => 'Accident', 'notes' => 'Engineer'
            ],
            [
                'first_name' => 'Patricia', 'last_name' => 'Miller',
                'dob' => '1948-07-17', 'dod' => '2020-09-09',
                'gender' => 'Female', 'cause' => 'Pneumonia', 'notes' => 'Grandmother of 5'
            ],
            [
                'first_name' => 'William', 'last_name' => 'Wilson',
                'dob' => '1935-01-02', 'dod' => '2018-11-30',
                'gender' => 'Male', 'cause' => 'Old Age', 'notes' => 'Veteran'
            ],
            [
                'first_name' => 'Barbara', 'last_name' => 'Moore',
                'dob' => '1952-04-23', 'dod' => '2021-05-17',
                'gender' => 'Female', 'cause' => 'Cancer', 'notes' => 'Artist'
            ],
            [
                'first_name' => 'James', 'last_name' => 'Taylor',
                'dob' => '1947-12-05', 'dod' => '2019-02-28',
                'gender' => 'Male', 'cause' => 'Heart Disease', 'notes' => 'Retired police officer'
            ],
            [
                'first_name' => 'Susan', 'last_name' => 'Anderson',
                'dob' => '1953-08-19', 'dod' => '2022-06-11',
                'gender' => 'Female', 'cause' => 'Kidney Failure', 'notes' => 'Community volunteer'
            ],
        ];

        $plotNumbers = ['A1', 'A2', 'B1', 'B2', 'C1', 'C2', 'D1', 'D2', 'E1', 'E2'];
        $sections = ['North', 'South', 'East', 'West', 'Central'];
        $rowNumbers = ['1', '2', '3', '4', '5'];

        foreach ($deceasedData as $index => $data) {
            // Deceased
            $deceased = new Deceased();
            $deceased->setFirstName($data['first_name']);
            $deceased->setLastName($data['last_name']);
            $deceased->setDateOfBirth(new \DateTime($data['dob']));
            $deceased->setDateOfDeath(new \DateTime($data['dod']));
            $deceased->setGender($data['gender']);
            $deceased->setCauseOfDeath($data['cause']);
            $deceased->setNotes($data['notes']);
            $manager->persist($deceased);

            // Burial Record
            $record = new BurialRecord();
            $record->setDeacesed($deceased);
            $record->setBurialDate((clone new \DateTime($data['dod']))->modify('+7 days'));
            $record->setFuneralHome("Peaceful Rest Funeral Home");
            $record->setRecordCreatedAt(new \DateTime());
            $record->setRecordUpdatedAt(new \DateTime());
            $record->setNotes("Burial record for {$data['first_name']} {$data['last_name']}");
            $manager->persist($record);

            // Burial Plot
            $plot = new BurialPlot();
            $plot->setPlotNumber($plotNumbers[$index]);
            $plot->setSection($sections[array_rand($sections)]);
            $plot->setRowNum($rowNumbers[array_rand($rowNumbers)]);
            $plot->setIsOccupied(true);
            $plot->setSize('Standard');
            $plot->setNotes("Plot for {$data['first_name']} {$data['last_name']}");
            $plot->setBurialRecord($record);
            $manager->persist($plot);

            $record->addPlotId($plot);
        }

        $manager->flush();
    }
}
