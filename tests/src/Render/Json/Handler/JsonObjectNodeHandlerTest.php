<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Render\Json\Handler;

use Avro\Node\JsonObjectNode;
use Avro\Shared\Position;
use Avro\Tests\AvroTestCase;
use Avro\Render\Json\Handler\JsonObjectNodeHandler;
use Avro\Render\Json\HandlerContext;
use Avro\Write\BufferedWriter;

/**
 * @covers \Avro\Render\Json\Handler\JsonObjectNodeHandler
 * @uses   \Avro\Node\JsonNode
 * @uses   \Avro\Node\JsonObjectNode
 * @uses   \Avro\Render\Json\HandlerAbstract
 * @uses   \Avro\Render\Json\HandlerContext
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Shared\Position
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 * @uses   \Avro\Write\BufferedWriter
 */
class JsonObjectNodeHandlerTest extends AvroTestCase
{
    public function testVisitWithoutSiblings(): void
    {
        $node = new JsonObjectNode(new Position(0, 0));
        $writer = new BufferedWriter();
        $handler = new JsonObjectNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals('{', $writer->getBuffer());
    }

    public function testVisitWithSiblings(): void
    {
        $node = new JsonObjectNode(new Position(0, 0));
        $node->addNode(new JsonObjectNode(new Position(0, 0)));
        $node->addNode(new JsonObjectNode(new Position(0, 0)));

        $writer = new BufferedWriter();
        $handler = new JsonObjectNodeHandler(new HandlerContext($writer));

        /** @psalm-suppress PossiblyNullArgument */
        $handler->visit($node->nodeAt(1));

        $this->assertEquals(', {', $writer->getBuffer());
    }

    public function testLeave(): void
    {
        $node = new JsonObjectNode(new Position(0, 0));
        $writer = new BufferedWriter();
        $handler = new JsonObjectNodeHandler(new HandlerContext($writer));
        $handler->leave($node);

        $this->assertEquals('}', $writer->getBuffer());
    }
}
