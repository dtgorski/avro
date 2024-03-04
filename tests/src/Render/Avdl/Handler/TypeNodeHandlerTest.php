<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Render\Avdl\Handler;

use Avro\AvroName;
use Avro\Node\FieldDeclarationNode;
use Avro\Node\MessageDeclarationNode;
use Avro\Node\TypeNode;
use Avro\Tests\AvroTestCase;
use Avro\Render\Avdl\Handler\TypeNodeHandler;
use Avro\Render\Avdl\HandlerContext;
use Avro\Write\BufferedWriter;

/**
 * @covers \Avro\Render\Avdl\Handler\TypeNodeHandler
 * @uses   \Avro\AvroName
 * @uses   \Avro\AvroNamespace
 * @uses   \Avro\Node\DeclarationNode
 * @uses   \Avro\Node\FieldDeclarationNode
 * @uses   \Avro\Node\MessageDeclarationNode
 * @uses   \Avro\Node\TypeNode
 * @uses   \Avro\Render\Avdl\HandlerAbstract
 * @uses   \Avro\Render\Avdl\HandlerContext
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Comments
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 * @uses   \Avro\Write\BufferedWriter
 */
class TypeNodeHandlerTest extends AvroTestCase
{
    public function testVisit(): void
    {
        $node = new TypeNode();
        $writer = new BufferedWriter();
        $handler = new TypeNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals('', $writer->getBuffer());
    }

    public function testVisitWithParentDeclaration(): void
    {
        $node = new FieldDeclarationNode();
        $node = $node->addNode(new TypeNode());

        $writer = new BufferedWriter();
        $handler = new TypeNodeHandler(new HandlerContext($writer));

        /** @psalm-suppress PossiblyNullArgument */
        $handler->visit($node->nodeAt(0));

        $this->assertEquals('', $writer->getBuffer());
    }

    public function testLeaveWithSiblings(): void
    {
        $node = new MessageDeclarationNode(AvroName::fromString('foo'));
        $node->addNode(new TypeNode());
        $node->addNode(new TypeNode());

        $writer = new BufferedWriter();
        $handler = new TypeNodeHandler(new HandlerContext($writer));

        /** @psalm-suppress PossiblyNullArgument */
        $handler->leave($node->nodeAt(0));

        $this->assertEquals(', ', $writer->getBuffer());
    }

    public function testLeaveWithoutSiblings(): void
    {
        $node = new TypeNode();
        $writer = new BufferedWriter();
        $handler = new TypeNodeHandler(new HandlerContext($writer));
        $handler->leave($node);

        $this->assertEquals('', $writer->getBuffer());
    }

    public function testLeaveNullable(): void
    {
        $node = new TypeNode(true);
        $writer = new BufferedWriter();
        $handler = new TypeNodeHandler(new HandlerContext($writer));
        $handler->leave($node);

        $this->assertEquals('?', $writer->getBuffer());
    }
}
