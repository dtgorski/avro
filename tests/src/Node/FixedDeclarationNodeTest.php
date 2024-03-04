<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Node;

use Avro\AvroName;
use Avro\Node\FixedDeclarationNode;
use Avro\Tests\AvroTestCase;

/**
 * @covers \Avro\Node\FixedDeclarationNode
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
class FixedDeclarationNodeTest extends AvroTestCase
{
    public function testGetName(): void
    {
        $name = AvroName::fromString('foo');
        $type = new FixedDeclarationNode($name, 42);
        $this->assertSame($name, $type->getName());
    }

    public function testGetValue(): void
    {
        $type = new FixedDeclarationNode(AvroName::fromString('foo'), 42);
        $this->assertSame(42, $type->getValue());
    }
}
