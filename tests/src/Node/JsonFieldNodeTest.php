<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Node;

use Avro\AvroName;
use Avro\Node\JsonFieldNode;
use Avro\Shared\Position;
use Avro\Tests\AvroTestCase;

/**
 * @covers \Avro\Node\JsonFieldNode
 * @uses   \Avro\AvroName
 * @uses   \Avro\Node\JsonNode
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Shared\Position
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 */
class JsonFieldNodeTest extends AvroTestCase
{
    public function testGetName(): void
    {
        $name = AvroName::fromString('foo');
        $type = new JsonFieldNode($name, new Position(0, 0));
        $this->assertSame($name, $type->getName());
    }

    public function testJsonSerialize(): void
    {
        $type = new JsonFieldNode(AvroName::fromString('foo'), new Position(0, 0));
        $this->assertSame('"foo"', json_encode($type));
    }
}
