<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Render;

use Avro\Tests\AvroTestCase;
use Avro\Tree\AstNode;
use Avro\Render\HandlerDelegate;
use Avro\Render\NodeHandler;

/**
 * @covers \Avro\Render\HandlerDelegate
 * @uses   \Avro\Render\NodeHandler
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 */
class HandlerDelegateTest extends AvroTestCase
{
    public function testAddHandler(): void
    {
        $handler = $this->createMock(NodeHandler::class);
        $delegate = new HandlerDelegate();
        $delegate->addHandler($handler);
        $this->assertSame($handler, $delegate->getHandlers()[0]);
    }

    public function testVisit(): void
    {
        $node = new DelegateTestNode();

        $handlerA = $this->createMock(NodeHandler::class);
        $handlerA->method('canHandle')->willReturn(false);
        $handlerA->expects($this->never())->method('visit');

        $handlerB = $this->createMock(NodeHandler::class);
        $handlerB->method('canHandle')->willReturn(true);
        $handlerB->expects($this->once())->method('visit')->with($node)->willReturn(true);

        $delegate = new HandlerDelegate([
            $handlerA,
            $handlerB,
        ]);

        $this->assertTrue($delegate->visit($node));
    }

    public function testVisitReturnsTrue(): void
    {
        $node = new DelegateTestNode();
        $delegate = new HandlerDelegate([]);

        $this->assertTrue($delegate->visit($node));
    }

    public function testLeave(): void
    {
        $node = new DelegateTestNode();

        $handlerA = $this->createMock(NodeHandler::class);
        $handlerA->method('canHandle')->willReturn(false);
        $handlerA->expects($this->never())->method('leave');

        $handlerB = $this->createMock(NodeHandler::class);
        $handlerB->method('canHandle')->willReturn(true);
        $handlerB->expects($this->once())->method('leave')->with($node);

        $delegate = new HandlerDelegate([
            $handlerA,
            $handlerB,
        ]);

        $delegate->leave($node);
    }

    public function testGetHandlers(): void
    {
        $handlers = [];
        $delegate = new HandlerDelegate($handlers);

        $this->assertSame($handlers, $delegate->getHandlers());
    }
}

// phpcs:ignore
class DelegateTestNode extends AstNode
{
}
