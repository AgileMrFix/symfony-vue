<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CustomerFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $customer = new Customer();
            $customer->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setEmail($faker->email);
            $manager->persist($customer);
        }

        $manager->flush();
    }

}
