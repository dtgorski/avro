<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Tree;

use Avro\Tests\AvroTestCase;
use Avro\Tree\Comment;

/**
 * @covers \Avro\Tree\Comment
 * @uses   \Avro\Parse\Token
 */
class CommentTest extends AvroTestCase
{
    public function testFromStringSingleLineComment(): void
    {
        $str = '/** foo */';
        $com = Comment::fromString($str);

        $this->assertEquals('foo', $com->getValue());
    }

    public function testFromStringMultilineLineComment(): void
    {
        $str = "/**\n";
        $str .= "foo\n";
        $str .= "bar\n";
        $str .= "*/\n";
        $com = Comment::fromString($str);

        $this->assertEquals("foo\nbar", $com->getValue());
    }
}
