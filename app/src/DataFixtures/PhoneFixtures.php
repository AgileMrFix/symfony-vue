<?php

namespace App\DataFixtures;

use App\Config\PhoneType;
use App\Entity\Customer;
use App\Entity\Phone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PhoneFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $customers = $manager->getRepository(Customer::class)->findAll();
        foreach ($customers as $customer) {

            //random phones count for customer
            $count = rand(0, 4);

            for ($i = 0; $i < $count; $i++) {
                $phone = new Phone();
                $phone->setCustomer($customer)
                    ->setNumber($faker->phoneNumber)
                    ->setType($faker->randomElement(PhoneType::cases()));
                $manager->persist($phone);
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CustomerFixtures::class,
        ];
    }
}
