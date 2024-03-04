<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Render\Avdl\Handler;

use Avro\Node\ErrorListNode;
use Avro\Tests\AvroTestCase;
use Avro\Type\ErrorType;
use Avro\Render\Avdl\Handler\ErrorListNodeHandler;
use Avro\Render\Avdl\HandlerContext;
use Avro\Write\BufferedWriter;

/**
 * @covers \Avro\Render\Avdl\Handler\ErrorListNodeHandler
 * @uses   \Avro\AvroNamespace
 * @uses   \Avro\Node\DeclarationNode
 * @uses   \Avro\Node\ErrorDeclarationNode
 * @uses   \Avro\Node\ErrorListNode
 * @uses   \Avro\Render\Avdl\HandlerAbstract
 * @uses   \Avro\Render\Avdl\HandlerContext
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Comments
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 * @uses   \Avro\Type\ErrorType
 * @uses   \Avro\Write\BufferedWriter
 */
class ErrorListNodeHandlerTest extends AvroTestCase
{
    public function testVisit(): void
    {
        $node = new ErrorListNode(ErrorType::THROWS);
        $writer = new BufferedWriter();
        $handler = new ErrorListNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals(' throws ', $writer->getBuffer());
    }
}
