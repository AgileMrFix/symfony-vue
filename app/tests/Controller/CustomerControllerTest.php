<?php

namespace App\Test\Controller\Api;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CustomerControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private string $path = '/';

    protected function setUp(): void
    {
        $this->client = static::createClient();

    }

    public function testIndex(): void
    {
        $this->client->request('GET', $this->path);
        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Customers');
    }

}
