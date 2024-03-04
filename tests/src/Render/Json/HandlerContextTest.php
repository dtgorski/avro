<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Render\Json;

use Avro\Tests\AvroTestCase;
use Avro\Render\Json\HandlerContext;
use Avro\Write\Writer;

/**
 * @covers \Avro\Render\Json\HandlerContext
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
}
