<?php

namespace App\Tests\Repository;

use App\Entity\Trick;
use App\Entity\TypeTricks;
use App\Repository\TricksRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TricksRepositoryTest extends KernelTestCase
{
    private TricksRepository $tricksRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $container = static::getContainer();
        $this->tricksRepository = $container->get(TricksRepository::class);
    }

    public function testSaveAndFind(): void
    {
        $trick = new Trick();
        $trick->setName('test');
        $trick->setDescription('test description');
        $this->tricksRepository->save($trick, true);
        $id = $trick->getId();
        $this->assertNotNull($id);

        $trick = $this->tricksRepository->find($id);
        $this->assertInstanceOf(Trick::class, $trick);
        $this->assertEquals('test', $trick->getName());
    }

    public function testRemove(): void
    {
        $trick = new Trick();
        $trick->setName('test');
        $trick->setDescription('test description');
        $this->tricksRepository->save($trick, true);

        $id = $trick->getId();
        $this->assertNotNull($id);

        $this->tricksRepository->remove($trick, true);

        $trick = $this->tricksRepository->find($id);
        $this->assertNull($trick);
    }


    public function testGetAllTricksWithType(): void
    {
        $tricks = $this->tricksRepository->getAllTricksWithType();
        $this->assertIsArray($tricks);

        foreach ($tricks as $trick) {
            $this->assertInstanceOf(Trick::class, $trick);
            $this->assertInstanceOf(TypeTricks::class, $trick->getType());
        }
    }
}