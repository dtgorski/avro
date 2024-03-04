<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Node;

use Avro\AvroName;
use Avro\Node\JsonFieldNode;
use Avro\Node\JsonObjectNode;
use Avro\Node\JsonValueNode;
use Avro\Shared\Position;
use Avro\Tests\AvroTestCase;

/**
 * @covers \Avro\Node\JsonObjectNode
 * @uses   \Avro\AvroName
 * @uses   \Avro\Node\JsonFieldNode
 * @uses   \Avro\Node\JsonNode
 * @uses   \Avro\Node\JsonValueNode
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Shared\Position
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 */
class JsonObjectNodeTest extends AvroTestCase
{
    public function testJsonSerialize(): void
    {
        $type = new JsonObjectNode(new Position(0, 0));

        $node1 = new JsonFieldNode(AvroName::fromString('foo'), new Position(0, 0));
        $node2 = new JsonValueNode(true, new Position(0, 0));
        $type->addNode(($node1)->addNode($node2));

        $node1 = new JsonFieldNode(AvroName::fromString('bar'), new Position(0, 0));
        $node2 = new JsonValueNode(false, new Position(0, 0));
        $type->addNode($node1->addNode($node2));

        $this->assertSame('{"foo":true,"bar":false}', json_encode($type));
    }
}
