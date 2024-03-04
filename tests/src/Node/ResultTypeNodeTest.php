<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Node;

use Avro\Node\ResultTypeNode;
use Avro\Tests\AvroTestCase;

/**
 * @covers \Avro\Node\ResultTypeNode
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 */
class ResultTypeNodeTest extends AvroTestCase
{
    public function testIsVoid(): void
    {
        $type = new ResultTypeNode(true);
        $this->assertSame(true, $type->isVoid());
    }
}
