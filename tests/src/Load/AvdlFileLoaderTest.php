<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Load;

use Avro\AvroName;
use Avro\AvroFileMap;
use Avro\AvroFilePath;
use Avro\Node\ProtocolDeclarationNode;
use Avro\Load\AvdlFileLoader;
use Avro\Tests\AvroTestCase;

/**
 * @covers \Avro\Load\AvdlFileLoader
 * @uses   \Avro\AvroFileMap
 * @uses   \Avro\AvroFilePath
 * @uses   \Avro\AvroName
 * @uses   \Avro\AvroNamespace
 * @uses   \Avro\AvroReference
 * @uses   \Avro\Load\FileLoader
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
 * @uses   \Avro\Render\Walker
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Shared\Position
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Comment
 * @uses   \Avro\Tree\Comments
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\Property
 * @uses   \Avro\Tree\TreeNode
 */
class AvdlFileLoaderTest extends AvroTestCase
{
    public function testProtoLoad(): void
    {
        $path = sprintf('%s/../../data/proto-01-in.avdl', __DIR__);
        $filename = AvroFilePath::fromString($path);

        $loader = new AvdlFileLoader();
        $fileMap = $loader->load($filename);

        $this->assertInstanceOf(ProtocolDeclarationNode::class, $fileMap->getIterator()->current());
    }

    public function testProtoExists(): void
    {
        $path = sprintf('%s/../../data/proto-01-in.avdl', __DIR__);
        $filename = AvroFilePath::fromString($path);

        $protoNode = new ProtocolDeclarationNode(AvroName::fromString('foo'));

        $fileMap = new AvroFileMap();
        $fileMap->set($filename, $protoNode);

        $loader = new AvdlFileLoader($fileMap);
        $loader->load($filename);

        $this->assertEquals(1, sizeof($fileMap->asArray()));
        $this->assertSame($protoNode, $fileMap->getIterator()->current());
    }

    public function testThrowsExceptionWhenCanNotReadFile(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/unable to read file/');

        $loader = new AvdlFileLoader();
        $loader->load(AvroFilePath::fromString('/tmp/'));
    }

    public function testThrowsExceptionWhenCanNotParseFile(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/expected @namespace/');

        $loader = new AvdlFileLoader();
        $loader->load(AvroFilePath::fromString(__FILE__));
    }
}
