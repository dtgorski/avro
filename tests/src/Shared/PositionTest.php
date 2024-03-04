<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Shared;

use Avro\Shared\Position;
use Avro\Tests\AvroTestCase;

/**
 * @covers \Avro\Shared\Position
 */
class PositionTest extends AvroTestCase
{
    public function test(): void
    {
        $pos = new Position(1, 2);
        $this->assertSame(1, $pos->getLine());
        $this->assertSame(2, $pos->getColumn());
    }
}
