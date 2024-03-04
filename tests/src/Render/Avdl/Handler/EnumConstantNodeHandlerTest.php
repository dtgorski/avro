<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Render\Avdl\Handler;

use Avro\AvroName;
use Avro\Node\EnumConstantNode;
use Avro\Node\EnumDeclarationNode;
use Avro\Tests\AvroTestCase;
use Avro\Render\Avdl\Handler\EnumConstantNodeHandler;
use Avro\Render\Avdl\HandlerContext;
use Avro\Write\BufferedWriter;

/**
 * @covers \Avro\Render\Avdl\Handler\EnumConstantNodeHandler
 * @uses   \Avro\AvroName
 * @uses   \Avro\AvroNamespace
 * @uses   \Avro\Node\DeclarationNode
 * @uses   \Avro\Node\EnumConstantNode
 * @uses   \Avro\Node\EnumDeclarationNode
 * @uses   \Avro\Node\NamedDeclarationNode
 * @uses   \Avro\Render\Avdl\HandlerAbstract
 * @uses   \Avro\Render\Avdl\HandlerContext
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Comments
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 * @uses   \Avro\Write\BufferedWriter
 */
class EnumConstantNodeHandlerTest extends AvroTestCase
{
    public function testVisit(): void
    {
        $node = new EnumConstantNode(AvroName::fromString('foo'));
        $writer = new BufferedWriter();
        $handler = new EnumConstantNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals('foo', $writer->getBuffer());
    }

    public function testLeaveWithoutSiblingNodes(): void
    {
        $node = new EnumConstantNode(AvroName::fromString('foo'));
        $writer = new BufferedWriter();
        $handler = new EnumConstantNodeHandler(new HandlerContext($writer));
        $handler->leave($node);

        $this->assertEquals("\n", $writer->getBuffer());
    }

    public function testLeaveWithSiblingNodes(): void
    {
        $node = new EnumDeclarationNode(AvroName::fromString('foo'), 'bar');
        $node->addNode(new EnumConstantNode(AvroName::fromString('foo')));
        $node->addNode(new EnumConstantNode(AvroName::fromString('bar')));

        $writer = new BufferedWriter();
        $handler = new EnumConstantNodeHandler(new HandlerContext($writer));

        /** @psalm-suppress PossiblyNullArgument */
        $handler->leave($node->nodeAt(0));

        $this->assertEquals(",\n", $writer->getBuffer());
    }
}
