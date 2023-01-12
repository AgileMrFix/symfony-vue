<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Customer;
use App\Entity\State;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AddressFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $customers = $manager->getRepository(Customer::class)->findAll();
        $states = $manager->getRepository(State::class)->findAll();

        foreach ($customers as  $customer){
            /** @var Customer $customer */
            /** @var State $state */

            //random customer not has address
            if (!rand(0,5))
                continue;

            $addr = new Address();

            $state = $states[array_rand($states)];
            $addr->setCustomer($customer)
                ->setCountry($state->getCountry())
                ->setState($state)
                ->setPostalCode($faker->postcode)
                ->setLine1($faker->streetAddress)
                ->setLine2(rand(0,1) ? $faker->randomDigit : null);

         $manager->persist($addr);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CountryStateFixtures::class,
            CustomerFixtures::class
        ];
    }
}
