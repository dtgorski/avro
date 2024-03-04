<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Render\Avdl\Handler;

use Avro\AvroName;
use Avro\Node\FormalParametersNode;
use Avro\Node\MessageDeclarationNode;
use Avro\Tests\AvroTestCase;
use Avro\Render\Avdl\Handler\FormalParametersNodeHandler;
use Avro\Render\Avdl\HandlerContext;
use Avro\Write\BufferedWriter;

/**
 * @covers \Avro\Render\Avdl\Handler\FormalParametersNodeHandler
 * @uses   \Avro\AvroName
 * @uses   \Avro\AvroNamespace
 * @uses   \Avro\Node\DeclarationNode
 * @uses   \Avro\Node\FormalParameterNode
 * @uses   \Avro\Node\MessageDeclarationNode
 * @uses   \Avro\Render\Avdl\HandlerAbstract
 * @uses   \Avro\Render\Avdl\HandlerContext
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Comments
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 * @uses   \Avro\Write\BufferedWriter
 */
class FormalParametersNodeHandlerTest extends AvroTestCase
{
    public function testVisit(): void
    {
        $parent = new MessageDeclarationNode(AvroName::fromString('foo'));
        $node = new FormalParametersNode();
        $parent->addNode($node);

        $writer = new BufferedWriter();
        $handler = new FormalParametersNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals(' foo(', $writer->getBuffer());
    }

    public function testLeave(): void
    {
        $node = new FormalParametersNode();
        $writer = new BufferedWriter();
        $handler = new FormalParametersNodeHandler(new HandlerContext($writer));
        $handler->leave($node);

        $this->assertEquals(')', $writer->getBuffer());
    }
}
