<?php

namespace App\Tests\Entity;

use App\Entity\Tricks;
use PHPUnit\Framework\TestCase;
use App\Entity\TypesTricks;

class TricksTest extends TestCase
{
    public function testTricks()
    {
        $typeTrick = new TypesTricks();
        $typeTrick->setName('TestType');

        $tricks = new Tricks();
        $tricks->setId(10000);
        $tricks->setName('TestTricks');
        $tricks->setType($typeTrick);
        $tricks->setDescription("I'm a testing description for a testing tricks.");

        $this->assertSame(10000, $tricks->getId());
        $this->assertSame('TestType', $tricks->getType()->getName());
        $this->assertSame('TestTricks', $tricks->getName());
        $this->assertSame('I\'m a testing description for a testing tricks.', $tricks->getDescription());
    }
}