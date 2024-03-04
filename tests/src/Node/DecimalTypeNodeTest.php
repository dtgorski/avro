<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Node;

use Avro\Node\DecimalTypeNode;
use Avro\Tests\AvroTestCase;
use Avro\Tree\Properties;

/**
 * @covers \Avro\Node\DecimalTypeNode
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 */
class DecimalTypeNodeTest extends AvroTestCase
{
    public function testGetPrecision(): void
    {
        $type = new DecimalTypeNode(10, 2, Properties::fromEmpty());
        $this->assertSame(10, $type->getPrecision());
    }

    public function testGetScale(): void
    {
        $type = new DecimalTypeNode(10, 2);
        $this->assertSame(2, $type->getScale());
    }

    // From <https://avro.apache.org/docs/1.11.1/specification/_print/#decimal>
    //
    // The following attributes are supported:
    //
    //   * precision, a JSON integer representing the (maximum) precision of decimals stored in this type (required).
    //   * scale, a JSON integer representing the scale (optional). If not specified the scale is 0.
    //
    // Precision must be a positive integer greater than zero.
    // Scale must be zero or a positive integer less than or equal to the precision.

    public function testThrowExceptionOnInvalidPrecision(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('unexpected invalid decimal type precision');
        new DecimalTypeNode(0, 2);
    }

    public function testThrowExceptionOnInvalidScale(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('unexpected invalid decimal type scale');
        new DecimalTypeNode(10, -1);
    }

    public function testThrowExceptionWhenScaleGraterThanPrecision(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('unexpected invalid decimal type scale');
        new DecimalTypeNode(1, 2);
    }
}
