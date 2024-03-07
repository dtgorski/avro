<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Parse;

use Avro\Node\ImportStatementNode;
use Avro\Node\MessageDeclarationNode;
use Avro\Parse\AvdlParser;
use Avro\Parse\ByteStreamReader;
use Avro\Parse\CommentSaveCursor;
use Avro\Parse\Lexer;
use Avro\Parse\Token;
use Avro\Tests\AvroTestCase;

/**
 * @covers \Avro\Parse\ParserBase
 * @covers \Avro\Parse\AvdlParser
 * @covers \Avro\Parse\JsonParser
 *
 * @uses   \Avro\AvroName
 * @uses   \Avro\AvroNamespace
 * @uses   \Avro\AvroReference
 * @uses   \Avro\Node\DeclarationNode
 * @uses   \Avro\Node\ImportStatementNode
 * @uses   \Avro\Node\JsonNode
 * @uses   \Avro\Node\JsonValueNode
 * @uses   \Avro\Node\MessageDeclarationNode
 * @uses   \Avro\Node\NamedDeclarationNode
 * @uses   \Avro\Node\ProtocolDeclarationNode
 * @uses   \Avro\Node\ReferenceTypeNode
 * @uses   \Avro\Node\ResultTypeNode
 * @uses   \Avro\Node\TypeNode
 * @uses   \Avro\Parse\ByteStreamReader
 * @uses   \Avro\Parse\CommentAwareCursor
 * @uses   \Avro\Parse\CommentSaveCursor
 * @uses   \Avro\Parse\CommentStack
 * @uses   \Avro\Parse\Lexer
 * @uses   \Avro\Parse\PropertiesWithNamespace
 * @uses   \Avro\Parse\Token
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Shared\Position
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Comments
 * @uses   \Avro\Tree\Property
 * @uses   \Avro\Tree\TreeNode
 */
class AvdlParserTest extends AvroTestCase
{
    /** @psalm-suppress PropertyNotSetInConstructor */
    private Lexer $lexer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->lexer = new Lexer();
    }

    /** @param resource $stream */
    protected function createParser($stream): AvdlParser
    {
        $tokens = $this->lexer->createTokenStream(new ByteStreamReader($stream));
        return new AvdlParser(new CommentSaveCursor($tokens));
    }

    public function testImportStatement(): void
    {
        $stream = $this->createStream('protocol foo { import idl "bar"; }');
        $parser = $this->createParser($stream);

        $node = $parser->parse();

        /** @psalm-suppress PossiblyNullReference */
        $this->assertInstanceOf(ImportStatementNode::class, $node->nodeAt(0));
    }

    public function testMessageDeclaration(): void
    {
        $stream = $this->createStream('protocol `foo` { bar.baz.A B(); }');
        $parser = $this->createParser($stream);

        $node = $parser->parse();

        /** @psalm-suppress PossiblyNullReference */
        $this->assertInstanceOf(MessageDeclarationNode::class, $node->nodeAt(0));
    }

    public function testExpectEOF(): void
    {
        $stream = $this->createStream('');
        $parser = $this->createParser($stream);

        $this->assertFalse($parser->expect(Token::TICK));
    }

    public function testThrowsExceptionWhenConsumeEOF(): void
    {
        $stream = $this->createStream('');
        $parser = $this->createParser($stream);

        $this->expectException(\Exception::class);
        $this->assertFalse($parser->consume(Token::TICK));
    }

    public function testThrowsExceptionWhenConsumeWithHintEOF(): void
    {
        $stream = $this->createStream('');
        $parser = $this->createParser($stream);

        $this->expectException(\Exception::class);
        $this->assertFalse($parser->consumeWithHint(Token::TICK, ''));
    }

    public function testThrowsExceptionWhenConsumeWrongTokenType(): void
    {
        $stream = $this->createStream('foo');
        $parser = $this->createParser($stream);

        $this->expectException(\Exception::class);
        $this->assertFalse($parser->consume(Token::TICK));
    }

    public function testThrowsExceptionWhenConsumeWithHintWrongTokenType(): void
    {
        $stream = $this->createStream('foo');
        $parser = $this->createParser($stream);

        $this->expectException(\Exception::class);
        $this->assertFalse($parser->consumeWithHint(Token::TICK, ''));
    }

    public function testThrowsExceptionWhenConsumeUnacceptedTokenLoad(): void
    {
        $stream = $this->createStream('foo');
        $parser = $this->createParser($stream);

        $this->expectException(\Exception::class);
        $this->assertFalse($parser->consume(Token::IDENT, 'bar'));
    }

    public function testThrowsExceptionWhenConsumeWithHintUnacceptedTokenLoad(): void
    {
        $stream = $this->createStream('foo');
        $parser = $this->createParser($stream);

        $this->expectException(\Exception::class);
        $this->assertFalse($parser->consumeWithHint(Token::IDENT, '', 'bar'));
    }

    public function testThrowsExceptionWhenInvalidDecimalTypePrecision(): void
    {
        $stream = $this->createStream('protocol x { decimal(-1, 0) foo(); } ');
        $parser = $this->createParser($stream);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/unexpected negative decimal type precision/');
        $parser->parse();
    }

    public function testThrowsExceptionWhenInvalidDecimalTypeScale(): void
    {
        $stream = $this->createStream('protocol x { decimal(0, -1) foo(); } ');
        $parser = $this->createParser($stream);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/unexpected invalid decimal type scale/');
        $parser->parse();
    }

    public function testThrowsExceptionWhenInvalidNamespace(): void
    {
        $stream = $this->createStream('@namespace(null)');
        $parser = $this->createParser($stream);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/value to be string/');
        $parser->parse();
    }
}
