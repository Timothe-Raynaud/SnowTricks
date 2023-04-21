<?php

namespace App\Tests\Entity;

use App\Entity\Tricks;
use PHPUnit\Framework\TestCase;
use App\Entity\TypesTricks;

class TricksTest extends TestCase
{
    public function testDefault()
    {
        $tricks = new Tricks();
        $tricks->setName('TestTricks');
        $tricks->setType();
        $tricks->setDescription("I'm a testing description for a testing tricks.");
    }
}