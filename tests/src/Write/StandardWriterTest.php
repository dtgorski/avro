<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Write;

use Avro\Tests\AvroTestCase;
use Avro\Write\StandardWriter;

/**
 * @covers \Avro\Write\StandardWriter
 */
class StandardWriterTest extends AvroTestCase
{
    public function testWriteString(): void
    {
        $writer = new StandardWriter();

        ob_start();
        $writer->write("foo\n");
        $this->assertEquals("foo\n", ob_get_clean());
    }
}
