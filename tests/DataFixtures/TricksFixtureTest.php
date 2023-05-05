<?php

namespace App\Tests\DataFixtures;

use App\Entity\Tricks;
use App\Entity\TypesTricks;
use App\DataFixtures\TricksFixture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TricksFixtureTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testTricksFixture(): void
    {
        $fixture = new TricksFixture();
        $fixture->load($this->entityManager);

        $typesTricks = $this->entityManager->getRepository(TypesTricks::class)->findAll();
        $this->assertCount(12, $typesTricks);

        $tricks = $this->entityManager->getRepository(Tricks::class)->findAll();
        $this->assertCount(20, $tricks);

        $this->assertEquals('Grabs', $tricks[0]->getType()->getName());
    }
}
