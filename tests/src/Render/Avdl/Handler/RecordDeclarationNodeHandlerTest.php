<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Render\Avdl\Handler;

use Avro\AvroName;
use Avro\Node\RecordDeclarationNode;
use Avro\Tests\AvroTestCase;
use Avro\Render\Avdl\Handler\RecordDeclarationNodeHandler;
use Avro\Render\Avdl\HandlerContext;
use Avro\Write\BufferedWriter;

/**
 * @covers \Avro\Render\Avdl\Handler\RecordDeclarationNodeHandler
 * @uses   \Avro\AvroName
 * @uses   \Avro\AvroNamespace
 * @uses   \Avro\Node\DeclarationNode
 * @uses   \Avro\Node\NamedDeclarationNode
 * @uses   \Avro\Node\RecordDeclarationNode
 * @uses   \Avro\Render\Avdl\HandlerAbstract
 * @uses   \Avro\Render\Avdl\HandlerContext
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Comments
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 * @uses   \Avro\Write\BufferedWriter
 */
class RecordDeclarationNodeHandlerTest extends AvroTestCase
{
    public function testVisit(): void
    {
        $node = new RecordDeclarationNode(AvroName::fromString('foo'));
        $writer = new BufferedWriter();
        $handler = new RecordDeclarationNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals("\nrecord foo {\n", $writer->getBuffer());
    }

    public function testLeave(): void
    {
        $node = new RecordDeclarationNode(AvroName::fromString('foo'));
        $writer = new BufferedWriter();
        $handler = new RecordDeclarationNodeHandler(new HandlerContext($writer));
        $handler->visit($node);
        $handler->leave($node);

        $this->assertEquals("\nrecord foo {\n}\n", $writer->getBuffer());
    }
}
