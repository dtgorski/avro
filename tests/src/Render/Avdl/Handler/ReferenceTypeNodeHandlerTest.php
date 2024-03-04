<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Render\Avdl\Handler;

use Avro\AvroReference;
use Avro\Node\ReferenceTypeNode;
use Avro\Tests\AvroTestCase;
use Avro\Render\Avdl\Handler\ReferenceTypeNodeHandler;
use Avro\Render\Avdl\HandlerContext;
use Avro\Write\BufferedWriter;

/**
 * @covers \Avro\Render\Avdl\Handler\ReferenceTypeNodeHandler
 * @uses   \Avro\AvroName
 * @uses   \Avro\AvroNamespace
 * @uses   \Avro\AvroReference
 * @uses   \Avro\Node\ReferenceTypeNode
 * @uses   \Avro\Render\Avdl\HandlerAbstract
 * @uses   \Avro\Render\Avdl\HandlerContext
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 * @uses   \Avro\Write\BufferedWriter
 */
class ReferenceTypeNodeHandlerTest extends AvroTestCase
{
    public function testVisit(): void
    {
        $node = new ReferenceTypeNode(AvroReference::fromString('foo'));
        $writer = new BufferedWriter();
        $handler = new ReferenceTypeNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals('foo', $writer->getBuffer());
    }
}
