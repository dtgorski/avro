<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Parse;

use Avro\Parse\ByteStreamReader;
use Avro\Parse\CommentAwareCursor;
use Avro\Parse\JsonParser;
use Avro\Parse\Lexer;
use Avro\Tests\AvroTestCase;

/**
 * @covers \Avro\Parse\JsonParser
 * @uses   \Avro\AvroName
 * @uses   \Avro\Node\JsonFieldNode
 * @uses   \Avro\Node\JsonNode
 * @uses   \Avro\Node\JsonObjectNode
 * @uses   \Avro\Node\JsonValueNode
 * @uses   \Avro\Parse\ByteStreamReader
 * @uses   \Avro\Parse\CommentAwareCursor
 * @uses   \Avro\Parse\JsonParser
 * @uses   \Avro\Parse\Lexer
 * @uses   \Avro\Parse\ParserBase
 * @uses   \Avro\Parse\Token
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Shared\Position
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 */
class JsonParserTest extends AvroTestCase
{
    public function testThrowsExceptionOnUnexpectedToken(): void
    {
        $stream = $this->createStream('!');

        $reader = new ByteStreamReader($stream);
        $cursor = new CommentAwareCursor((new Lexer())->createTokenStream($reader));

        $parser = new JsonParser($cursor);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/unexpected input/');

        $parser->parse();
    }

    public function testThrowsExceptionOnInvalidJson(): void
    {
        $stream = $this->createStream('invalid');

        $reader = new ByteStreamReader($stream);
        $cursor = new CommentAwareCursor((new Lexer())->createTokenStream($reader));

        $parser = new JsonParser($cursor);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/valid JSON/');

        $parser->parse();
    }

    // Has not been covered by AvdlParserTest.
    public function testParseObjectWithMultipleFields(): void
    {
        $stream = $this->createStream('{"x": 1, "y": 2, "z": 3}');

        $reader = new ByteStreamReader($stream);
        $cursor = new CommentAwareCursor((new Lexer())->createTokenStream($reader));

        $parser = new JsonParser($cursor);
        $node = $parser->parse();

        $this->assertEquals(3, $node->nodeCount());
    }
}
