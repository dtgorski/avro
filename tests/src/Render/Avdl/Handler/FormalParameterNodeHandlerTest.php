<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Render\Avdl\Handler;

use Avro\Node\FormalParameterNode;
use Avro\Node\FormalParametersNode;
use Avro\Tests\AvroTestCase;
use Avro\Render\Avdl\Handler\FormalParameterNodeHandler;
use Avro\Render\Avdl\HandlerContext;
use Avro\Write\BufferedWriter;

/**
 * @covers \Avro\Render\Avdl\Handler\FormalParameterNodeHandler
 * @uses   \Avro\AvroNamespace
 * @uses   \Avro\Node\FormalParameterNode
 * @uses   \Avro\Render\Avdl\HandlerAbstract
 * @uses   \Avro\Render\Avdl\HandlerContext
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 * @uses   \Avro\Write\BufferedWriter
 */
class FormalParameterNodeHandlerTest extends AvroTestCase
{
    public function testVisitWithoutSiblingNodes(): void
    {
        $node = new FormalParameterNode();
        $writer = new BufferedWriter();
        $handler = new FormalParameterNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals('', $writer->getBuffer());
    }

    public function testVisitWithSiblingNodes(): void
    {
        $node = new FormalParametersNode();
        $node->addNode(new FormalParameterNode());
        $node->addNode(new FormalParameterNode());

        $writer = new BufferedWriter();
        $handler = new FormalParameterNodeHandler(new HandlerContext($writer));

        /** @psalm-suppress PossiblyNullArgument */
        $handler->visit($node->nodeAt(1));

        $this->assertEquals(', ', $writer->getBuffer());
    }
}
