<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Render\Json\Handler;

use Avro\Node\JsonArrayNode;
use Avro\Shared\Position;
use Avro\Tests\AvroTestCase;
use Avro\Render\Json\Handler\JsonArrayNodeHandler;
use Avro\Render\Json\HandlerContext;
use Avro\Write\BufferedWriter;

/**
 * @covers \Avro\Render\Json\Handler\JsonArrayNodeHandler
 * @uses   \Avro\Node\JsonArrayNode
 * @uses   \Avro\Node\JsonNode
 * @uses   \Avro\Render\Json\HandlerAbstract
 * @uses   \Avro\Render\Json\HandlerContext
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Shared\Position
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 * @uses   \Avro\Write\BufferedWriter
 */
class JsonArrayNodeHandlerTest extends AvroTestCase
{
    public function testVisitWithoutSiblings(): void
    {
        $node = new JsonArrayNode(new Position(0, 0));
        $writer = new BufferedWriter();
        $handler = new JsonArrayNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals('[', $writer->getBuffer());
    }

    public function testVisitWithSiblings(): void
    {
        $node = new JsonArrayNode(new Position(0, 0));
        $node->addNode(new JsonArrayNode(new Position(0, 0)));
        $node->addNode(new JsonArrayNode(new Position(0, 0)));

        $writer = new BufferedWriter();
        $handler = new JsonArrayNodeHandler(new HandlerContext($writer));

        /** @psalm-suppress PossiblyNullArgument */
        $handler->visit($node->nodeAt(1));

        $this->assertEquals(', [', $writer->getBuffer());
    }

    public function testLeave(): void
    {
        $node = new JsonArrayNode(new Position(0, 0));
        $writer = new BufferedWriter();
        $handler = new JsonArrayNodeHandler(new HandlerContext($writer));
        $handler->leave($node);

        $this->assertEquals(']', $writer->getBuffer());
    }
}
