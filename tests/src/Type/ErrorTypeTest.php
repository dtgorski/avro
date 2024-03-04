<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Type;

use Avro\Tests\AvroTestCase;
use Avro\Type\ErrorType;

/**
 * @covers \Avro\Type\ErrorType
 */
class ErrorTypeTest extends AvroTestCase
{
    public function testErrorType(): void
    {
        $this->assertSame('THROWS', ErrorType::THROWS->name);
        $this->assertSame('throws', ErrorType::THROWS->value);

        $this->assertSame('ONEWAY', ErrorType::ONEWAY->name);
        $this->assertSame('oneway', ErrorType::ONEWAY->value);

        $this->assertEquals(ErrorType::names(), ErrorType::names());
    }

    public function testHasType(): void
    {
        $this->assertTrue(ErrorType::hasType('throws'));
        $this->assertTrue(ErrorType::hasType('oneway'));
    }
}
