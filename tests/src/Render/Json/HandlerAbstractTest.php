<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Render\Json;

use Avro\Tests\AvroTestCase;
use Avro\Tree\AstNode;
use Avro\Render\Json\HandlerAbstract;
use Avro\Render\Json\HandlerContext;
use Avro\Visitable;
use Avro\Write\BufferedWriter;
use Avro\Write\Writer;

/**
 * @covers \Avro\Render\Json\HandlerAbstract
 * @uses   \Avro\Render\Json\HandlerContext
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 * @uses   \Avro\Write\BufferedWriter
 * @uses   \Avro\Write\Writer
 */
class HandlerAbstractTest extends AvroTestCase
{
    public function testVisitReturnsTrue(): void
    {
        $writer = $this->createMock(Writer::class);
        $ctx = new HandlerContext($writer);
        $handler = new BogusHandlerAbstract($ctx);

        $return = $handler->visit(new BogusAstNode());
        $this->assertTrue($return);
    }

    public function testLeave(): void
    {
        $writer = $this->createMock(Writer::class);
        $ctx = new HandlerContext($writer);
        $handler = new BogusHandlerAbstract($ctx);
        $handler->leave(new BogusAstNode());

        $this->assertTrue(true);
    }

    public function testWrite(): void
    {
        $writer = new BufferedWriter();
        $ctx = new HandlerContext($writer);
        $handler = new BogusHandlerAbstract($ctx);

        $handler->write('foo', 'bar');

        $this->assertEquals('foobar', $writer->getBuffer());
    }
}

// phpcs:disable
class BogusHandlerAbstract extends HandlerAbstract
{
    public function canHandle(Visitable $node): bool
    {
        return true;
    }

    public function write(string|float|int|null ...$args): void
    {
        parent::write(...$args);
    }
}

class BogusAstNode extends AstNode
{
}
