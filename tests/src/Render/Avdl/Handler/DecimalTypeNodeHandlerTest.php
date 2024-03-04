<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Render\Avdl\Handler;

use Avro\Node\DecimalTypeNode;
use Avro\Tests\AvroTestCase;
use Avro\Render\Avdl\Handler\DecimalTypeNodeHandler;
use Avro\Render\Avdl\HandlerContext;
use Avro\Write\BufferedWriter;

/**
 * @covers \Avro\Render\Avdl\Handler\DecimalTypeNodeHandler
 * @uses   \Avro\Node\DecimalTypeNode
 * @uses   \Avro\Render\Avdl\HandlerAbstract
 * @uses   \Avro\Render\Avdl\HandlerContext
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 * @uses   \Avro\Write\BufferedWriter
 */
class DecimalTypeNodeHandlerTest extends AvroTestCase
{
    public function testVisit(): void
    {
        $node = new DecimalTypeNode(2, 1);
        $writer = new BufferedWriter();
        $handler = new DecimalTypeNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals('decimal(2, 1)', $writer->getBuffer());
    }

    public function testLeave(): void
    {
        $node = new DecimalTypeNode(2, 1);
        $writer = new BufferedWriter();
        $handler = new DecimalTypeNodeHandler(new HandlerContext($writer));
        $handler->leave($node);

        $this->assertEquals('', $writer->getBuffer());
    }
}
