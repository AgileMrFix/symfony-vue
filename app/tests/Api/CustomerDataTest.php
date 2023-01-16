<?php

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Entity\Country;
use App\Entity\Customer;
use App\Repository\CountryRepository;
use App\Repository\CustomerRepository;
use Faker\Factory;
use Faker\Generator;

class CustomerDataTest extends ApiTestCase
{
    private Client $client;
    private CustomerRepository $repository;
    private Generator $faker;

    private string $path = '/api/customers';

    protected function setUp(): void
    {
        self::bootKernel();
        $this->client = static::createClient(defaultOptions: ['headers' => [
            'Accept' => 'application/json',
        ]]);

        $this->repository = static::getContainer()->get('doctrine')->getRepository(Customer::class);
        $this->faker = Factory::create();
    }


    public function testListWithoutParameters(): void
    {
        $this->client->request('GET', $this->path);

        $response = $this->client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertCount(count($this->repository->findAll()), $response->toArray());
    }

    public function testListWithSearchMustReturnOneOrMoreResults(): void
    {
        /** @var Customer $randomElement */
        $randomElement = $this->faker->randomElement($this->repository->findAll());
        $this->client->request('GET', $this->path, ['query' => ['search' => $randomElement->getLastName()]]);

        $response = $this->client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertTrue(count($response->toArray()) >= 1);
    }

    public function testListCanBeOrdered(): void
    {
        $this->client->request('GET', $this->path, ['query' => ['order' => ['first_name' => 'asc']]]);

        $response = $this->client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertTrue(count($response->toArray()) >= 1);
    }

    public function testListCanBeFiltered(): void
    {

        /** @var CountryRepository $countryRepository */
        $countryRepository = static::getContainer()->get('doctrine')->getRepository(Country::class);
        $countryId = $this->faker->randomElement($countryRepository->findAll())->getId();

        $this->client->request('GET', $this->path, ['query' => ['address.country.id' => $countryId]]);

        $response = $this->client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertNotCount(count($response->toArray()), $this->repository->findAll());
    }


}
