<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Render\Diag;

use Avro\Avro;
use Avro\Tests\AvroTestCase;
use Avro\Render\Diag\AstDumper;
use Avro\Write\BufferedWriter;

/**
 * @covers \Avro\Render\Diag\AstDumper
 * @uses   \Avro\Avro
 * @uses   \Avro\AvroFileMap
 * @uses   \Avro\AvroFilePath
 * @uses   \Avro\AvroName
 * @uses   \Avro\AvroNamespace
 * @uses   \Avro\AvroProtocol
 * @uses   \Avro\AvroReference
 * @uses   \Avro\Load\AvdlFileLoader
 * @uses   \Avro\Node\DecimalTypeNode
 * @uses   \Avro\Node\DeclarationNode
 * @uses   \Avro\Node\EnumConstantNode
 * @uses   \Avro\Node\EnumDeclarationNode
 * @uses   \Avro\Node\ErrorListNode
 * @uses   \Avro\Node\FieldDeclarationNode
 * @uses   \Avro\Node\FixedDeclarationNode
 * @uses   \Avro\Node\JsonArrayNode
 * @uses   \Avro\Node\JsonFieldNode
 * @uses   \Avro\Node\JsonNode
 * @uses   \Avro\Node\JsonObjectNode
 * @uses   \Avro\Node\JsonValueNode
 * @uses   \Avro\Node\LogicalTypeNode
 * @uses   \Avro\Node\MessageDeclarationNode
 * @uses   \Avro\Node\NamedDeclarationNode
 * @uses   \Avro\Node\PrimitiveTypeNode
 * @uses   \Avro\Node\ProtocolDeclarationNode
 * @uses   \Avro\Node\ReferenceTypeNode
 * @uses   \Avro\Node\ResultTypeNode
 * @uses   \Avro\Node\TypeNode
 * @uses   \Avro\Node\VariableDeclaratorNode
 * @uses   \Avro\Parse\AvdlParser
 * @uses   \Avro\Parse\ByteStreamReader
 * @uses   \Avro\Parse\CommentAwareCursor
 * @uses   \Avro\Parse\CommentSaveCursor
 * @uses   \Avro\Parse\CommentStack
 * @uses   \Avro\Parse\JsonParser
 * @uses   \Avro\Parse\Lexer
 * @uses   \Avro\Parse\ParserBase
 * @uses   \Avro\Parse\PropertiesWithNamespace
 * @uses   \Avro\Parse\Token
 * @uses   \Avro\Render\Walker
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Shared\Position
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Comment
 * @uses   \Avro\Tree\Comments
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\Property
 * @uses   \Avro\Tree\TreeNode
 * @uses   \Avro\Write\BufferedWriter
 */
class AstDumperTest extends AvroTestCase
{
    /** @dataProvider provideInputOutput */
    public function test(string $input, string $output): void
    {
        $avro = new Avro();

        $source = sprintf('%s/../../../data/%s', __DIR__, $input);
        $target = sprintf('%s/../../../data/%s', __DIR__, $output);

        $writer = new BufferedWriter();
        $proto = $avro->loadProtocol($source);
        $node = $proto->getFileMap()->getIterator()->current();

        $renderer = new AstDumper($writer);
        $node->accept($renderer);

        $expect = file_get_contents($target);

        $this->assertEquals($expect, $writer->getBuffer());
    }

    public static function provideInputOutput(): array
    {
        return [
            ["proto-01-in.avdl", "proto-01-out.dump"],
            ["proto-02-in.avdl", "proto-02-out.dump"],
        ];
    }
}
