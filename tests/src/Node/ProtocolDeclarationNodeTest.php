<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Node;

use Avro\AvroName;
use Avro\Node\ProtocolDeclarationNode;
use Avro\Tests\AvroTestCase;

/**
 * @covers \Avro\Node\ProtocolDeclarationNode
 * @uses   \Avro\AvroName
 * @uses   \Avro\AvroNamespace
 * @uses   \Avro\Node\DeclarationNode
 * @uses   \Avro\Node\NamedDeclarationNode
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Comments
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 */
class ProtocolDeclarationNodeTest extends AvroTestCase
{
    public function testGetName(): void
    {
        $name = AvroName::fromString('foo');
        $type = new ProtocolDeclarationNode($name);
        $this->assertSame($name, $type->getName());
    }
}
