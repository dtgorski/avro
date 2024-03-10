<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Tree;

use Avro\Tests\AvroTestCase;
use Avro\Tree\Comment;
use Avro\Tree\Comments;

/**
 * @covers \Avro\Tree\Comments
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Tree\Comment
 */
class CommentsTest extends AvroTestCase
{
    public function testComments(): void
    {
        $comment1 = Comment::fromString('foo');
        $comment2 = Comment::fromString('bar');

        $comments = Comments::fromKeyValue([$comment1, $comment2]);

        $this->assertSame(2, $comments->size());
        $this->assertEquals([$comment1, $comment2], $comments->asArray());
        $this->assertSame($comment1, $comments->getIterator()->current());
    }
}
