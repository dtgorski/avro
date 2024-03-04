<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Node;

use Avro\Node\JsonArrayNode;
use Avro\Node\JsonValueNode;
use Avro\Shared\Position;
use Avro\Tests\AvroTestCase;

/**
 * @covers \Avro\Node\JsonArrayNode
 * @uses   \Avro\Node\JsonNode
 * @uses   \Avro\Node\JsonValueNode
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Shared\Position
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 */
class JsonArrayNodeTest extends AvroTestCase
{
    public function testJsonSerialize(): void
    {
        $type = new JsonArrayNode(new Position(0, 0));

        $type->addNode(new JsonValueNode(true, new Position(0, 0)));
        $type->addNode(new JsonValueNode(false, new Position(0, 0)));

        $this->assertSame('[true,false]', json_encode($type));
    }
}
