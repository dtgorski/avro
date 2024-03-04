<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests;

use Avro\Avro;
use Avro\AvroFileMap;
use Avro\AvroProtocol;
use Avro\Write\BufferedWriter;

/**
 * @covers \Avro\AvroProtocol
 * @uses   \Avro\Avro
 * @uses   \Avro\AvroFileMap
 * @uses   \Avro\AvroFilePath
 * @uses   \Avro\AvroName
 * @uses   \Avro\AvroNamespace
 * @uses   \Avro\AvroReference
 * @uses   \Avro\Load\AvdlFileLoader
 * @uses   \Avro\Node\DeclarationNode
 * @uses   \Avro\Node\EnumConstantNode
 * @uses   \Avro\Node\EnumDeclarationNode
 * @uses   \Avro\Node\ErrorDeclarationNode
 * @uses   \Avro\Node\ErrorListNode
 * @uses   \Avro\Node\FieldDeclarationNode
 * @uses   \Avro\Node\FixedDeclarationNode
 * @uses   \Avro\Node\JsonArrayNode
 * @uses   \Avro\Node\JsonNode
 * @uses   \Avro\Node\JsonValueNode
 * @uses   \Avro\Node\MessageDeclarationNode
 * @uses   \Avro\Node\NamedDeclarationNode
 * @uses   \Avro\Node\PrimitiveTypeNode
 * @uses   \Avro\Node\ProtocolDeclarationNode
 * @uses   \Avro\Node\RecordDeclarationNode
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
 * @uses   \Avro\Render\Diag\AstDumper
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
class AvroProtocolTest extends AvroTestCase
{
    public function testFromFileMap(): void
    {
        $fileMap = new AvroFileMap();
        $proto = AvroProtocol::fromFileMap($fileMap);

        $this->assertEquals($fileMap, $proto->getFileMap());
    }

    public function testLoad(): void
    {
        $path = sprintf('%s/../data/proto-01-in.avdl', __DIR__);
        $proto = (new Avro())->loadProtocol($path);

        $this->assertTrue($proto->getFileMap()->size() > 0);
    }

    public function testDump(): void
    {
        $path = sprintf('%s/../data/proto-01-in.avdl', __DIR__);
        $proto = (new Avro())->loadProtocol($path);

        $writer = new BufferedWriter();
        $proto->dump($writer);

        $this->assertTrue(strlen($writer->getBuffer()) > 0);
    }
}
