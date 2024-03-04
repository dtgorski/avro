<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Render\Avdl\Handler;

use Avro\Node\ResultTypeNode;
use Avro\Tests\AvroTestCase;
use Avro\Render\Avdl\Handler\ResultTypeNodeHandler;
use Avro\Render\Avdl\HandlerContext;
use Avro\Write\BufferedWriter;

/**
 * @covers \Avro\Render\Avdl\Handler\ResultTypeNodeHandler
 * @uses   \Avro\Node\ResultTypeNode
 * @uses   \Avro\Render\Avdl\HandlerAbstract
 * @uses   \Avro\Render\Avdl\HandlerContext
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 * @uses   \Avro\Write\BufferedWriter
 */
class ResultTypeNodeHandlerTest extends AvroTestCase
{
    public function testVisitVoid(): void
    {
        $node = new ResultTypeNode(true);
        $writer = new BufferedWriter();
        $handler = new ResultTypeNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals('void', $writer->getBuffer());
    }

    public function testVisitNonVoid(): void
    {
        $node = new ResultTypeNode(false);
        $writer = new BufferedWriter();
        $handler = new ResultTypeNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertEquals('', $writer->getBuffer());
    }
}
