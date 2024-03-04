<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Write;

use Avro\Tests\AvroTestCase;
use Avro\Write\BufferedWriter;

/**
 * @covers \Avro\Write\BufferedWriter
 */
class BufferedWriterTest extends AvroTestCase
{
    public function testWrite(): void
    {
        $writer = new BufferedWriter();

        $writer->write("foo\n");
        $writer->write("bar\n");
        $this->assertEquals("foo\nbar\n", $writer->getBuffer());

        $writer->clearBuffer();
        $this->assertEquals('', $writer->getBuffer());
    }
}
