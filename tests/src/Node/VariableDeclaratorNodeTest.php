<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Node;

use Avro\AvroName;
use Avro\Node\VariableDeclaratorNode;
use Avro\Tests\AvroTestCase;

/**
 * @covers \Avro\Node\VariableDeclaratorNode
 * @uses   \Avro\AvroName
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 */
class VariableDeclaratorNodeTest extends AvroTestCase
{
    public function testGetName(): void
    {
        $name = AvroName::fromString('foo');
        $type = new VariableDeclaratorNode($name);
        $this->assertSame($name, $type->getName());
    }
}
