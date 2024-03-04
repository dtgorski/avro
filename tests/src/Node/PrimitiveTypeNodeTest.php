<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Node;

use Avro\Node\PrimitiveTypeNode;
use Avro\Tests\AvroTestCase;
use Avro\Type\PrimitiveType;

/**
 * @covers \Avro\Node\PrimitiveTypeNode
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 */
class PrimitiveTypeNodeTest extends AvroTestCase
{
    public function testGetName(): void
    {
        $type = new PrimitiveTypeNode(PrimitiveType::INT);
        $this->assertSame('INT', $type->getType()->name);
        $this->assertSame('int', $type->getType()->value);
    }
}
