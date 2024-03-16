<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Render\Avdl\Handler;

use Avro\Node\OnewayStatementNode;
use Avro\Node\TypeNode;
use Avro\Tests\AvroTestCase;
use Avro\Render\Avdl\Handler\OnewayStatementNodeHandler;
use Avro\Render\Avdl\HandlerContext;
use Avro\Write\BufferedWriter;

/**
 * @covers \Avro\Render\Avdl\Handler\OnewayStatementNodeHandler
 * @uses   \Avro\Node\TypeNode
 * @uses   \Avro\Render\Avdl\HandlerAbstract
 * @uses   \Avro\Render\Avdl\HandlerContext
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 * @uses   \Avro\Write\BufferedWriter
 */
class OnewayStatementNodeHandlerTest extends AvroTestCase
{
    public function testVisit(): void
    {
        $node = new OnewayStatementNode();
        $writer = new BufferedWriter();
        $handler = new OnewayStatementNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals(' oneway', $writer->getBuffer());

        $this->assertTrue($handler->visit(new TypeNode()));
    }
}
