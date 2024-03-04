<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Node;

use Avro\Node\ImportStatementNode;
use Avro\Tests\AvroTestCase;
use Avro\Type\ImportType;

/**
 * @covers \Avro\Node\ImportStatementNode
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Node\DeclarationNode
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Comments
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 * @uses   \Avro\Type\ImportType
 */
class ImportStatementNodeTest extends AvroTestCase
{
    public function testGetType(): void
    {
        $type = new ImportStatementNode(ImportType::IDL, 'foo');
        $this->assertSame(ImportType::IDL, $type->getType());
    }

    public function testGetPath(): void
    {
        $type = new ImportStatementNode(ImportType::IDL, 'foo');
        $this->assertSame('foo', $type->getPath());
    }
}
