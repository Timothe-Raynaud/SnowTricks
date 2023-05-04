<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('#banner-home');
        $this->assertSelectorExists('#catch-phrase');
        $this->assertSelectorTextContains('#catch-phrase h3', 'Découvrez les secrets des plus grandes figures de snowboard sur notre plateforme de référence.');
    }
}