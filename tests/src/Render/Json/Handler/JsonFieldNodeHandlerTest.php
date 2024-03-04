<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Render\Json\Handler;

use Avro\AvroName;
use Avro\Node\JsonFieldNode;
use Avro\Node\JsonObjectNode;
use Avro\Shared\Position;
use Avro\Tests\AvroTestCase;
use Avro\Render\Json\Handler\JsonFieldNodeHandler;
use Avro\Render\Json\HandlerContext;
use Avro\Write\BufferedWriter;

/**
 * @covers \Avro\Render\Json\Handler\JsonFieldNodeHandler
 * @uses   \Avro\AvroName
 * @uses   \Avro\Node\JsonFieldNode
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
class JsonFieldNodeHandlerTest extends AvroTestCase
{
    public function testVisitWithoutSiblings(): void
    {
        $node = new JsonFieldNode(AvroName::fromString('foo'), new Position(0, 0));
        $writer = new BufferedWriter();
        $handler = new JsonFieldNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals('"foo":', $writer->getBuffer());
    }

    public function testVisitWithSiblings(): void
    {
        $node = new JsonObjectNode(new Position(0, 0));
        $node->addNode(new JsonFieldNode(AvroName::fromString('foo'), new Position(0, 0)));
        $node->addNode(new JsonFieldNode(AvroName::fromString('bar'), new Position(0, 0)));

        $writer = new BufferedWriter();
        $handler = new JsonFieldNodeHandler(new HandlerContext($writer));

        /** @psalm-suppress PossiblyNullArgument */
        $handler->visit($node->nodeAt(1));

        $this->assertEquals(', "bar":', $writer->getBuffer());
    }
}
