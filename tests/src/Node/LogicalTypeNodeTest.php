<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Node;

use Avro\Node\LogicalTypeNode;
use Avro\Tests\AvroTestCase;
use Avro\Type\LogicalType;

/**
 * @covers \Avro\Node\LogicalTypeNode
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 * @uses   \Avro\Type\LogicalType
 */
class LogicalTypeNodeTest extends AvroTestCase
{
    public function testGetName(): void
    {
        $type = new LogicalTypeNode(LogicalType::DATE);
        $this->assertSame('DATE', $type->getType()->name);
    }
}
