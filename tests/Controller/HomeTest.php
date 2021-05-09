<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpClient\TraceableHttpClient;

class HomeTest extends WebTestCase
{
    public function testHomePage(): void
    {   
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertSelectorTextContains('h1', 'Bonjour');
        $this->assertResponseIsSuccessful(200);
    }
}
