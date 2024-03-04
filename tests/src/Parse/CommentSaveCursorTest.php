<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Parse;

use Avro\Parse\ByteStreamReader;
use Avro\Parse\CommentSaveCursor;
use Avro\Parse\CommentStack;
use Avro\Parse\Lexer;
use Avro\Parse\Token;
use Avro\Tests\AvroTestCase;

/**
 * @covers \Avro\Parse\CommentSaveCursor
 * @uses   \Avro\Parse\ByteStreamReader
 * @uses   \Avro\Parse\CommentAwareCursor
 * @uses   \Avro\Parse\CommentStack
 * @uses   \Avro\Parse\Lexer
 * @uses   \Avro\Parse\Token
 * @uses   \Avro\Shared\Position
 * @uses   \Avro\Tree\Comment
 */
class CommentSaveCursorTest extends AvroTestCase
{
    public function testCursorPeek(): void
    {
        $stream = $this->createStream('/**/ foo . bar : record /**/ //');

        $reader = new ByteStreamReader($stream);
        $stack = new CommentStack();

        $cursor = new CommentSaveCursor(
            (new Lexer())->createTokenStream($reader),
            $stack
        );

        $this->assertEquals(Token::IDENT, $cursor->peek()->getType());
        $this->assertEquals('foo', $cursor->peek()->getLoad());
        $cursor->next();

        $this->assertEquals(Token::DOT, $cursor->peek()->getType());
        $cursor->next();

        $this->assertEquals(Token::IDENT, $cursor->peek()->getType());
        $this->assertEquals('bar', $cursor->peek()->getLoad());
        $cursor->next();

        $this->assertEquals(Token::COLON, $cursor->peek()->getType());
        $cursor->next();

        $this->assertEquals(Token::IDENT, $cursor->peek()->getType());
        $this->assertEquals('record', $cursor->peek()->getLoad());
        $cursor->next();

        $this->assertEquals(Token::EOF, $cursor->peek()->getType());
        $cursor->next();

        $this->closeStream($stream);

        $this->assertEquals(2, $stack->size());
    }

    public function testCursorNext(): void
    {
        $stream = $this->createStream('/**/ /**/ //');

        $reader = new ByteStreamReader($stream);
        $stack = new CommentStack();

        $cursor = new CommentSaveCursor(
            (new Lexer())->createTokenStream($reader),
            $stack
        );

        $this->assertEquals(Token::EOF, $cursor->next()->getType());

        $this->assertEquals(2, $stack->size());
    }
}
