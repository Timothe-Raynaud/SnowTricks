<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\TypesTricks;

class TypesTricksTest extends TestCase
{
    public function testTypesTricks()
    {
        $typeTrick = new TypesTricks();
        $typeTrick->setName('TestType');
        $typeTrick->setId(10000);

        $this->assertSame(10000, $typeTrick->getId());
        $this->assertSame('TestType', $typeTrick->getName());
    }
}