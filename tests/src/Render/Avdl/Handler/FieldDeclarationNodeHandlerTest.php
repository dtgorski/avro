<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Render\Avdl\Handler;

use Avro\Node\FieldDeclarationNode;
use Avro\Tests\AvroTestCase;
use Avro\Render\Avdl\Handler\FieldDeclarationNodeHandler;
use Avro\Render\Avdl\HandlerContext;
use Avro\Write\BufferedWriter;

/**
 * @covers \Avro\Render\Avdl\Handler\FieldDeclarationNodeHandler
 * @uses   \Avro\AvroNamespace
 * @uses   \Avro\Node\DeclarationNode
 * @uses   \Avro\Node\FieldDeclarationNode
 * @uses   \Avro\Render\Avdl\HandlerAbstract
 * @uses   \Avro\Render\Avdl\HandlerContext
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Comments
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 * @uses   \Avro\Write\BufferedWriter
 */
class FieldDeclarationNodeHandlerTest extends AvroTestCase
{
    public function testLeave(): void
    {
        $node = new FieldDeclarationNode();
        $writer = new BufferedWriter();
        $handler = new FieldDeclarationNodeHandler(new HandlerContext($writer));
        $handler->leave($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals(";\n", $writer->getBuffer());
    }
}
