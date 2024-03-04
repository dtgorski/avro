<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Parse;

use Avro\Parse\CommentStack;
use Avro\Tests\AvroTestCase;
use Avro\Tree\Comment;

/**
 * @covers \Avro\Parse\CommentStack
 * @uses   \Avro\Tree\Comment
 */
class CommentStackTest extends AvroTestCase
{
    public function testStack(): void
    {
        $c0 = $this->createMock(Comment::class);
        $c1 = $this->createMock(Comment::class);

        $stack = new CommentStack();
        $this->assertSame(0, $stack->size());

        $stack->push($c0);
        $stack->push($c1);
        $this->assertSame(2, $stack->size());

        $comments = $stack->drain();
        $this->assertSame(0, $stack->size());

        $this->assertSame($c0, $comments[0]);
        $this->assertSame($c1, $comments[1]);
    }
}
