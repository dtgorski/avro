<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Tree;

use Avro\Tests\AvroTestCase;
use Avro\Tree\Properties;
use Avro\Tree\Property;

/**
 * @covers \Avro\Tree\Properties
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Tree\Property
 */
class PropertiesTest extends AvroTestCase
{
    public function testProperties(): void
    {
        $property1 = new Property('foo', 1);
        $property2 = new Property('bar', 2);

        $properties = Properties::fromArray([$property1, $property2]);

        $this->assertSame($property1, $properties->getByName('foo'));
        $this->assertSame($property2, $properties->getByName('bar'));

        $this->assertSame(null, $properties->getByName(''));
        $this->assertSame(2, $properties->size());

        $this->assertEquals([$property1, $property2], $properties->asArray());
        $this->assertEquals('{"0":{"foo":1},"1":{"bar":2}}', json_encode($properties));
        $this->assertSame($property1, $properties->getIterator()->current());
    }
}
