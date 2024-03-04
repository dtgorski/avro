<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Node;

use Avro\Node\JsonNode;
use Avro\Shared\Position;
use Avro\Tests\AvroTestCase;

/**
 * @covers \Avro\Node\JsonNode
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Shared\Position
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 */
class JsonNodeTest extends AvroTestCase
{
    public function test(): void
    {
        $pos = new Position(0, 0);
        $node = new class ($pos) extends JsonNode {
        };
        $this->assertSame($pos, $node->getPosition());
    }
}
