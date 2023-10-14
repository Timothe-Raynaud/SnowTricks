<?php

namespace App\Tests\Repository;

use App\Entity\TypeTricks;
use App\Repository\TypesTricksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TypesTricksRepositoryTest extends KernelTestCase
{
    private TypesTricksRepository $typesTricksRepository;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $container = static::getContainer();
        $this->typesTricksRepository = $container->get(TypesTricksRepository::class);
    }

    public function testSaveAndFind(): void
    {
        $type = new TypeTricks();
        $type->setName('test');
        $this->typesTricksRepository->save($type, true);
        $id = $type->getId();
        $this->assertNotNull($id);

        $type = $this->typesTricksRepository->find($id);
        $this->assertInstanceOf(TypeTricks::class, $type);
        $this->assertEquals('test', $type->getName());
    }

    public function testRemove(): void
    {
        $type = new TypeTricks();
        $type->setName('test');
        $this->typesTricksRepository->save($type, true);

        $id = $type->getId();
        $this->assertNotNull($id);

        $this->typesTricksRepository->remove($type, true);

        $type = $this->typesTricksRepository->find($id);
        $this->assertNull($type);
    }
}