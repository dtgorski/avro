<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Node;

use Avro\AvroName;
use Avro\AvroNamespace;
use Avro\Node\NamedDeclarationNode;
use Avro\Tests\AvroTestCase;

/**
 * @covers \Avro\Node\NamedDeclarationNode
 * @uses   \Avro\AvroName
 * @uses   \Avro\AvroNamespace
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Node\DeclarationNode
 * @uses   \Avro\Node\NamedDeclarationNode
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Comments
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 */
class NamedDeclarationNodeTest extends AvroTestCase
{
    public function testGetName(): void
    {
        $name = AvroName::fromString('foo');
        $type = new NamedDeclarationNode($name);
        $this->assertSame($name, $type->getName());
    }


    public function testSetGetNamespace(): void
    {
        $name = AvroName::fromString('foo');
        $node = new class ($name) extends NamedDeclarationNode {
        };

        $this->assertSame('', $node->getNamespace()->getValue());

        $namespace = $this->createMock(AvroNamespace::class);
        $node->setNamespace($namespace);
        $this->assertSame($namespace, $node->getNamespace());
    }
}
