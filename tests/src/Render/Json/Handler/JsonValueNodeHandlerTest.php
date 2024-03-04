<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Render\Json\Handler;

use Avro\Node\JsonArrayNode;
use Avro\Node\JsonValueNode;
use Avro\Shared\Position;
use Avro\Tests\AvroTestCase;
use Avro\Render\Json\Handler\JsonValueNodeHandler;
use Avro\Render\Json\HandlerContext;
use Avro\Write\BufferedWriter;

/**
 * @covers \Avro\Render\Json\Handler\JsonValueNodeHandler
 * @uses   \Avro\Node\JsonArrayNode
 * @uses   \Avro\Node\JsonNode
 * @uses   \Avro\Node\JsonValueNode
 * @uses   \Avro\Render\Json\HandlerAbstract
 * @uses   \Avro\Render\Json\HandlerContext
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Shared\Position
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 * @uses   \Avro\Write\BufferedWriter
 */
class JsonValueNodeHandlerTest extends AvroTestCase
{
    public function testVisitWithoutSiblings(): void
    {
        $writer = new BufferedWriter();
        $handler = new JsonValueNodeHandler(new HandlerContext($writer));

        $node = new JsonValueNode(0.123, new Position(0, 0));
        $writer->clearBuffer();
        $handler->visit($node);
        $this->assertEquals('0.123', $writer->getBuffer());

        $node = new JsonValueNode("\33", new Position(0, 0));
        $writer->clearBuffer();
        $handler->visit($node);
        $this->assertEquals('"\u001b"', $writer->getBuffer());

        $node = new JsonValueNode(null, new Position(0, 0));
        $writer->clearBuffer();
        $handler->visit($node);
        $this->assertEquals('null', $writer->getBuffer());

        $node = new JsonValueNode(true, new Position(0, 0));
        $writer->clearBuffer();
        $handler->visit($node);
        $this->assertEquals('true', $writer->getBuffer());

        $node = new JsonValueNode(false, new Position(0, 0));
        $writer->clearBuffer();
        $handler->visit($node);
        $this->assertEquals('false', $writer->getBuffer());

        $this->assertTrue($handler->canHandle($node));
    }

    public function testVisitWithSiblings(): void
    {
        $node = new JsonArrayNode(new Position(0, 0));
        $node->addNode(new JsonValueNode(null, new Position(0, 0)));
        $node->addNode(new JsonValueNode(42, new Position(0, 0)));

        $writer = new BufferedWriter();
        $handler = new JsonValueNodeHandler(new HandlerContext($writer));

        /** @psalm-suppress PossiblyNullArgument */
        $handler->visit($node->nodeAt(1));

        $this->assertEquals(', 42', $writer->getBuffer());
    }
}
