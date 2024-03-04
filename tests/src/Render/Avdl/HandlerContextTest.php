<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Render\Avdl;

use Avro\Tests\AvroTestCase;
use Avro\Render\Avdl\HandlerContext;
use Avro\Write\Writer;

/**
 * @covers \Avro\Render\Avdl\HandlerContext
 * @uses   \Avro\Write\Writer
 */
class HandlerContextTest extends AvroTestCase
{
    public function testGetWriter(): void
    {
        $writer = $this->createMock(Writer::class);
        $ctx = new HandlerContext($writer);

        $this->assertSame($writer, $ctx->getWriter());
    }

    public function testManipulateDepth(): void
    {
        $writer = $this->createMock(Writer::class);
        $ctx = new HandlerContext($writer);

        $this->assertSame(0, $ctx->getDepth());

        $ctx->stepIn();
        $this->assertSame(1, $ctx->getDepth());

        $ctx->stepOut();
        $this->assertSame(0, $ctx->getDepth());

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('step underrun');

        $ctx->stepOut();
    }
}
