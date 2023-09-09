<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\TypeTricks;

class TypesTricksTest extends TestCase
{
    public function testTypesTricks()
    {
        $typeTrick = new TypeTricks();
        $typeTrick->setName('TestType');
        $typeTrick->setId(10000);

        $this->assertSame(10000, $typeTrick->getId());
        $this->assertSame('TestType', $typeTrick->getName());
    }
}