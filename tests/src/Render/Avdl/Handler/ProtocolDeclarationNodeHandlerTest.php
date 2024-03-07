<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Render\Avdl\Handler;

use Avro\AvroName;
use Avro\Node\ProtocolDeclarationNode;
use Avro\Tests\AvroTestCase;
use Avro\Render\Avdl\Handler\ProtocolDeclarationNodeHandler;
use Avro\Render\Avdl\HandlerContext;
use Avro\Write\BufferedWriter;

/**
 * @covers \Avro\Render\Avdl\Handler\ProtocolDeclarationNodeHandler
 * @uses   \Avro\AvroName
 * @uses   \Avro\AvroNamespace
 * @uses   \Avro\Node\DeclarationNode
 * @uses   \Avro\Node\NamedDeclarationNode
 * @uses   \Avro\Node\ProtocolDeclarationNode
 * @uses   \Avro\Render\Avdl\HandlerAbstract
 * @uses   \Avro\Render\Avdl\HandlerContext
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Comments
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 * @uses   \Avro\Write\BufferedWriter
 */
class ProtocolDeclarationNodeHandlerTest extends AvroTestCase
{
    public function testVisit(): void
    {
        $node = new ProtocolDeclarationNode(AvroName::fromString('foo'));
        $writer = new BufferedWriter();
        $handler = new ProtocolDeclarationNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals("protocol foo {\n", $writer->getBuffer());
    }

    public function testLeave(): void
    {
        $node = new ProtocolDeclarationNode(AvroName::fromString('foo'));
        $writer = new BufferedWriter();
        $handler = new ProtocolDeclarationNodeHandler(new HandlerContext($writer));
        $handler->visit($node);
        $handler->leave($node);

        $this->assertEquals("protocol foo {\n}\n", $writer->getBuffer());
    }
}
