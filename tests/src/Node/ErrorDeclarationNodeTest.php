<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Node;

use Avro\AvroName;
use Avro\Node\ErrorDeclarationNode;
use Avro\Tests\AvroTestCase;

/**
 * @covers \Avro\Node\ErrorDeclarationNode
 * @uses   \Avro\AvroName
 * @uses   \Avro\AvroNamespace
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Node\DeclarationNode
 * @uses   \Avro\Node\NamedDeclarationNode
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Comments
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 */
class ErrorDeclarationNodeTest extends AvroTestCase
{
    public function testGetName(): void
    {
        $name = AvroName::fromString('foo');
        $type = new ErrorDeclarationNode($name);
        $this->assertSame($name, $type->getName());
    }
}
