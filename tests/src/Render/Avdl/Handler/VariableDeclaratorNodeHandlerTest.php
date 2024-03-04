<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Render\Avdl\Handler;

use Avro\AvroName;
use Avro\Node\FormalParameterNode;
use Avro\Node\JsonValueNode;
use Avro\Node\VariableDeclaratorNode;
use Avro\Shared\Position;
use Avro\Tests\AvroTestCase;
use Avro\Render\Avdl\Handler\VariableDeclaratorNodeHandler;
use Avro\Render\Avdl\HandlerContext;
use Avro\Write\BufferedWriter;

/**
 * @covers \Avro\Render\Avdl\Handler\VariableDeclaratorNodeHandler
 * @uses   \Avro\AvroName
 * @uses   \Avro\Node\JsonNode
 * @uses   \Avro\Node\JsonValueNode
 * @uses   \Avro\Node\VariableDeclaratorNode
 * @uses   \Avro\Render\Avdl\HandlerAbstract
 * @uses   \Avro\Render\Avdl\HandlerContext
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Shared\Position
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 * @uses   \Avro\Write\BufferedWriter
 */
class VariableDeclaratorNodeHandlerTest extends AvroTestCase
{
    public function testVisitWithValue(): void
    {
        $node = new VariableDeclaratorNode(AvroName::fromString('foo'));
        $node->addNode(new JsonValueNode(true, new Position(0, 0)));
        $writer = new BufferedWriter();
        $handler = new VariableDeclaratorNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals(' foo = ', $writer->getBuffer());
    }

    public function testVisitWithoutValue(): void
    {
        $node = new VariableDeclaratorNode(AvroName::fromString('foo'));
        $writer = new BufferedWriter();
        $handler = new VariableDeclaratorNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertEquals(' foo', $writer->getBuffer());
    }

    public function testLeaveWithSiblingNodes(): void
    {
        $node = new FormalParameterNode();
        $node->addNode(new VariableDeclaratorNode(AvroName::fromString('foo')));
        $node->addNode(new VariableDeclaratorNode(AvroName::fromString('bar')));

        $writer = new BufferedWriter();
        $handler = new VariableDeclaratorNodeHandler(new HandlerContext($writer));

        /** @psalm-suppress PossiblyNullArgument */
        $handler->leave($node->nodeAt(0));

        $this->assertEquals(',', $writer->getBuffer());
    }

    public function testLeaveWithoutSiblingNodes(): void
    {
        $node = new VariableDeclaratorNode(AvroName::fromString('foo'));
        $writer = new BufferedWriter();
        $handler = new VariableDeclaratorNodeHandler(new HandlerContext($writer));
        $handler->leave($node);

        $this->assertEquals('', $writer->getBuffer());
    }
}
