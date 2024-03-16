<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Render\Avdl\Handler;

use Avro\Node\PrimitiveTypeNode;
use Avro\Node\TypeNode;
use Avro\Tests\AvroTestCase;
use Avro\Type\PrimitiveType;
use Avro\Render\Avdl\Handler\PrimitiveTypeNodeHandler;
use Avro\Render\Avdl\HandlerContext;
use Avro\Write\BufferedWriter;

/**
 * @covers \Avro\Render\Avdl\Handler\PrimitiveTypeNodeHandler
 * @uses   \Avro\Node\PrimitiveTypeNode
 * @uses   \Avro\Node\TypeNode
 * @uses   \Avro\Render\Avdl\HandlerAbstract
 * @uses   \Avro\Render\Avdl\HandlerContext
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 * @uses   \Avro\Write\BufferedWriter
 */
class PrimitiveTypeNodeHandlerTest extends AvroTestCase
{
    public function testVisit(): void
    {
        $node = new PrimitiveTypeNode(PrimitiveType::DOUBLE);
        $writer = new BufferedWriter();
        $handler = new PrimitiveTypeNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals('double', $writer->getBuffer());

        $this->assertTrue($handler->visit(new TypeNode()));
    }
}
