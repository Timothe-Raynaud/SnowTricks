<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TricksControllerTest extends WebTestCase
{
    public function testGetTricks()
    {
        $client = static::createClient();
        $client->request('GET', '/getTricks');

        $this->assertResponseIsSuccessful();

        $tricks = json_decode($client->getResponse()->getContent(), true);
        $this->assertNotEmpty($tricks);

        foreach ($tricks as $tricksRow) {
            foreach ($tricksRow as $trick) {
                $this->assertArrayHasKey('id', $trick);
                $this->assertArrayHasKey('name', $trick);
                $this->assertArrayHasKey('description', $trick);
                $this->assertArrayHasKey('type', $trick);
                $this->assertArrayHasKey('id', $trick['type']);
                $this->assertArrayHasKey('name', $trick['type']);
            }
        }
    }
}