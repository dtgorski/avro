<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Render\Avdl\Handler;

use Avro\Node\LogicalTypeNode;
use Avro\Tests\AvroTestCase;
use Avro\Tree\Properties;
use Avro\Tree\Property;
use Avro\Type\LogicalType;
use Avro\Render\Avdl\Handler\LogicalTypeNodeHandler;
use Avro\Render\Avdl\HandlerContext;
use Avro\Write\BufferedWriter;

/**
 * @covers \Avro\Render\Avdl\Handler\LogicalTypeNodeHandler
 * @uses   \Avro\Node\LogicalTypeNode
 * @uses   \Avro\Render\Avdl\HandlerAbstract
 * @uses   \Avro\Render\Avdl\HandlerContext
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\Property
 * @uses   \Avro\Tree\TreeNode
 * @uses   \Avro\Write\BufferedWriter
 */
class LogicalTypeNodeHandlerTest extends AvroTestCase
{
    public function testVisit(): void
    {
        $prop = Properties::fromKeyValue([
            'foo' => new Property('foo', 1),
            'bar' => new Property('bar', "\33"),
        ]);

        $node = new LogicalTypeNode(LogicalType::DATE, $prop);
        $writer = new BufferedWriter();
        $handler = new LogicalTypeNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals('@foo(1) @bar("\u001b") date', $writer->getBuffer());
    }
}
