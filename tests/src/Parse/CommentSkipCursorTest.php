<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Parse;

use Avro\Parse\ByteStreamReader;
use Avro\Parse\CommentSkipCursor;
use Avro\Parse\Lexer;
use Avro\Parse\Token;
use Avro\Tests\AvroTestCase;

/**
 * @covers \Avro\Parse\CommentSkipCursor
 * @uses   \Avro\Parse\ByteStreamReader
 * @uses   \Avro\Parse\CommentAwareCursor
 * @uses   \Avro\Parse\CommentStack
 * @uses   \Avro\Parse\Lexer
 * @uses   \Avro\Parse\Token
 * @uses   \Avro\Shared\Position
 */
class CommentSkipCursorTest extends AvroTestCase
{
    public function testCursorPeek(): void
    {
        $stream = $this->createStream('/**/ foo . bar : record /**/ //');

        $reader = new ByteStreamReader($stream);
        $cursor = new CommentSkipCursor((new Lexer())->createTokenStream($reader));

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
    }

    public function testCursorNext(): void
    {
        $stream = $this->createStream('/**/ /**/ //');

        $reader = new ByteStreamReader($stream);
        $cursor = new CommentSkipCursor((new Lexer())->createTokenStream($reader));

        $this->assertEquals(Token::EOF, $cursor->next()->getType());
    }
}
