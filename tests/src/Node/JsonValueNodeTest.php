<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Node;

use Avro\Node\JsonValueNode;
use Avro\Shared\Position;
use Avro\Tests\AvroTestCase;

/**
 * @covers \Avro\Node\JsonValueNode
 * @uses   \Avro\Node\JsonNode
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Shared\Position
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 */
class JsonValueNodeTest extends AvroTestCase
{
    public function testGetName(): void
    {
        $type = new JsonValueNode('foo', new Position(0, 0));
        $this->assertSame('foo', $type->getValue());
    }

    public function testJsonSerialize(): void
    {
        $type = new JsonValueNode('foo', new Position(0, 0));
        $this->assertSame('"foo"', json_encode($type));
    }
}
