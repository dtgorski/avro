<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Node;

use Avro\Node\DeclarationNode;
use Avro\Tests\AvroTestCase;
use Avro\Tree\Comment;
use Avro\Tree\Comments;

/**
 * @covers \Avro\Node\DeclarationNode
 * @uses   \Avro\AvroFilePath
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Comment
 * @uses   \Avro\Tree\Comments
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 */
class DeclarationNodeTest extends AvroTestCase
{
    public function testAddGetComments(): void
    {
        $node = new class extends DeclarationNode {
        };

        $this->assertSame(0, $node->getComments()->size());
        $node->setComments(Comments::fromKeyValue([Comment::fromString('foo'), Comment::fromString('bar')]));
        $this->assertSame(2, $node->getComments()->size());

        $test = function (Comment $comment, int $i): void {
            $expect = ['foo', 'bar'];
            $this->assertEquals($expect[$i], $comment->getValue());
        };

        $i = 0;
        foreach ($node->getComments() as $comment) {
            $test($comment, $i++);
        }
    }
}
