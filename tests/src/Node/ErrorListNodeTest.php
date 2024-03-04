<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Node;

use Avro\Node\ErrorListNode;
use Avro\Tests\AvroTestCase;
use Avro\Type\ErrorType;

/**
 * @covers \Avro\Node\ErrorListNode
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 * @uses   \Avro\Type\ErrorType
 */
class ErrorListNodeTest extends AvroTestCase
{
    public function testGetType(): void
    {
        $type = new ErrorListNode(ErrorType::ONEWAY);
        $this->assertEquals($type->getType()->name, ErrorType::ONEWAY->name);
    }
}
