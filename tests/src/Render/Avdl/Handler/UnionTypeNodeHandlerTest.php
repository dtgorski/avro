<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Render\Avdl\Handler;

use Avro\Node\TypeNode;
use Avro\Node\UnionTypeNode;
use Avro\Tests\AvroTestCase;
use Avro\Render\Avdl\Handler\UnionTypeNodeHandler;
use Avro\Render\Avdl\HandlerContext;
use Avro\Write\BufferedWriter;

/**
 * @covers \Avro\Render\Avdl\Handler\UnionTypeNodeHandler
 * @uses   \Avro\Node\TypeNode
 * @uses   \Avro\Render\Avdl\HandlerAbstract
 * @uses   \Avro\Render\Avdl\HandlerContext
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 * @uses   \Avro\Write\BufferedWriter
 */
class UnionTypeNodeHandlerTest extends AvroTestCase
{
    public function testVisitEmpty(): void
    {
        $node = new UnionTypeNode();
        $writer = new BufferedWriter();
        $handler = new UnionTypeNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals('union {', $writer->getBuffer());
    }

    public function testVisitNonEmpty(): void
    {
        $node = new UnionTypeNode();
        $node->addNode(new TypeNode());

        $writer = new BufferedWriter();
        $handler = new UnionTypeNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertEquals('union { ', $writer->getBuffer());
    }

    public function testLeave(): void
    {
        $node = new UnionTypeNode();
        $writer = new BufferedWriter();
        $handler = new UnionTypeNodeHandler(new HandlerContext($writer));
        $handler->visit($node);
        $handler->leave($node);

        $this->assertEquals('union { }', $writer->getBuffer());
    }
}
