<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Render\Avdl\Handler;

use Avro\Node\MapTypeNode;
use Avro\Tests\AvroTestCase;
use Avro\Render\Avdl\Handler\MapTypeNodeHandler;
use Avro\Render\Avdl\HandlerContext;
use Avro\Write\BufferedWriter;

/**
 * @covers \Avro\Render\Avdl\Handler\MapTypeNodeHandler
 * @uses   \Avro\Render\Avdl\HandlerAbstract
 * @uses   \Avro\Render\Avdl\HandlerContext
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 * @uses   \Avro\Write\BufferedWriter
 */
class MapTypeNodeHandlerTest extends AvroTestCase
{
    public function testVisit(): void
    {
        $node = new MapTypeNode();
        $writer = new BufferedWriter();
        $handler = new MapTypeNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals('map<', $writer->getBuffer());
    }

    public function testLeave(): void
    {
        $node = new MapTypeNode();
        $writer = new BufferedWriter();
        $handler = new MapTypeNodeHandler(new HandlerContext($writer));
        $handler->leave($node);

        $this->assertEquals('>', $writer->getBuffer());
    }
}
