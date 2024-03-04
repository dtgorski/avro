<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Render\Avdl\Handler;

use Avro\AvroName;
use Avro\Node\EnumDeclarationNode;
use Avro\Tests\AvroTestCase;
use Avro\Render\Avdl\Handler\EnumDeclarationNodeHandler;
use Avro\Render\Avdl\HandlerContext;
use Avro\Write\BufferedWriter;

/**
 * @covers \Avro\Render\Avdl\Handler\EnumDeclarationNodeHandler
 * @uses   \Avro\AvroName
 * @uses   \Avro\AvroNamespace
 * @uses   \Avro\Node\DeclarationNode
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
class EnumDeclarationNodeHandlerTest extends AvroTestCase
{
    public function testVisit(): void
    {
        $node = new EnumDeclarationNode(AvroName::fromString('foo'), 'bar');
        $writer = new BufferedWriter();
        $handler = new EnumDeclarationNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals("\nenum foo {\n", $writer->getBuffer());
    }

    public function testLeaveWithDefault(): void
    {
        $node = new EnumDeclarationNode(AvroName::fromString('foo'), 'bar');
        $writer = new BufferedWriter();
        $handler = new EnumDeclarationNodeHandler(new HandlerContext($writer));
        $handler->visit($node);
        $handler->leave($node);

        $this->assertEquals("\nenum foo {\n} = bar;\n", $writer->getBuffer());
    }

    public function testLeaveWithoutDefault(): void
    {
        $node = new EnumDeclarationNode(AvroName::fromString('foo'), '');
        $writer = new BufferedWriter();
        $handler = new EnumDeclarationNodeHandler(new HandlerContext($writer));
        $handler->visit($node);
        $handler->leave($node);

        $this->assertEquals("\nenum foo {\n}\n", $writer->getBuffer());
    }
}
