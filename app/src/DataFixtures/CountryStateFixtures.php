<?php

namespace App\DataFixtures;

use App\Entity\Country;
use App\Entity\State;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CountryStateFixtures extends Fixture
{
    private ObjectManager $manager;

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->makeCountries();

    }

    private function makeCountries(){
        $data = $this->getData();
        foreach ($data as $countryName => $stateNames) {
            $country = new Country();
            $country->setName($countryName);
            $this->manager->persist($country);
            $this->makeStates($country, $stateNames);
            $this->manager->flush();
        }
    }

    private function makeStates(Country $country, $stateNames)
    {
        foreach ($stateNames as $stateName){
            $state = new State();
            $state->setName($stateName);
            $state->setCountry($country);
            $this->manager->persist($state);
        }

    }

    private function getData()
    {
        return [
            'Canada' => ['British Columbia', 'Alberta'],
            'USA' => ['Washington', 'Los Angeles']
        ];
    }


}
