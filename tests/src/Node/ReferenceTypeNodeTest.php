<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Node;

use Avro\AvroReference;
use Avro\Node\ReferenceTypeNode;
use Avro\Tests\AvroTestCase;

/**
 * @covers \Avro\Node\ReferenceTypeNode
 * @uses   \Avro\AvroName
 * @uses   \Avro\AvroNamespace
 * @uses   \Avro\AvroReference
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 */
class ReferenceTypeNodeTest extends AvroTestCase
{
    public function testGetName(): void
    {
        $ref = AvroReference::fromString('foo.bar');
        $type = new ReferenceTypeNode($ref);
        $this->assertSame($ref, $type->getReference());
    }
}
