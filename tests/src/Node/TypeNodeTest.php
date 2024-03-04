<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 03/2024

declare(strict_types=1);

namespace Avro\Tests\Node;

use Avro\Node\TypeNode;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Avro\Node\TypeNode
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\TreeNode
 */
class TypeNodeTest extends TestCase
{
    public function testNullable(): void
    {
        $node = new TypeNode();
        $this->assertFalse($node->isNullable());

        $node = new TypeNode(true);
        $this->assertTrue($node->isNullable());
    }
}
