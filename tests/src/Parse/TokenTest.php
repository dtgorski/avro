<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Parse;

use Avro\Parse\Token;
use Avro\Tests\AvroTestCase;

/**
 * @covers \Avro\Parse\Token
 * @uses   \Avro\Shared\Position
 */
class TokenTest extends AvroTestCase
{
    public function testIntegrity(): void
    {
        $token = new Token(1, '4', 2, 3);

        $this->assertSame(1, $token->getType());
        $this->assertSame(2, $token->getPosition()->getLine());
        $this->assertSame(3, $token->getPosition()->getColumn());
        $this->assertSame('4', $token->getLoad());

        $this->assertTrue($token->is(1));
        $this->assertTrue($token->isNot(0));

        $this->assertSame(1, $token->getType());
        $this->assertSame('input', $token->getSymbol());
        $this->assertSame('%s', $token->getFormat());

        $this->assertSame('ERR', Token::type(1));
        $this->assertSame('input', Token::symbol(1));

        $this->assertIsString(Token::format(1));
        $this->assertIsString(Token::format(42));
    }
}
