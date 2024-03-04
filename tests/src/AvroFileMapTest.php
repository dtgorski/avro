<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests;

use Avro\AvroFileMap;
use Avro\AvroFilePath;
use Avro\Visitable;

/**
 * @covers \Avro\AvroFileMap
 * @uses   \Avro\AvroFilePath
 */
class AvroFileMapTest extends AvroTestCase
{
    public function testSetterAndPredicate(): void
    {
        $srcFile1 = $this->createMock(AvroFilePath::class);
        $srcFile1->method('getPath')->willReturn('foo');

        $srcFile2 = $this->createMock(AvroFilePath::class);
        $srcFile2->method('getPath')->willReturn('bar');

        $treeNode1 = $this->createMock(Visitable::class);
        $treeNode2 = $this->createMock(Visitable::class);

        $map = new AvroFileMap();

        $map->set($srcFile1, $treeNode1);
        $map->set($srcFile2, $treeNode2);

        $this->assertTrue($map->has($srcFile1));
        $this->assertTrue($map->has($srcFile2));
    }

    public function testGetIterator(): void
    {
        $srcFile1 = $this->createMock(AvroFilePath::class);
        $srcFile1->method('getPath')->willReturn('foo');

        $srcFile2 = $this->createMock(AvroFilePath::class);
        $srcFile2->method('getPath')->willReturn('bar');

        $treeNode1 = $this->createMock(Visitable::class);
        $treeNode2 = $this->createMock(Visitable::class);

        $map = new AvroFileMap();
        $map->set($srcFile1, $treeNode1);
        $map->set($srcFile2, $treeNode2);
        $map->set($srcFile2, $treeNode2);

        $it = $map->getIterator();
        $this->assertSame($treeNode1, $it->current());
        $it->next();
        $this->assertSame($treeNode2, $it->current());
        $it->next();
        $this->assertSame(null, $it->current());
    }

    public function testAsArray(): void
    {
        $this->assertEquals([], (new AvroFileMap())->asArray());
    }

    public function testSize(): void
    {
        $srcFile = $this->createMock(AvroFilePath::class);
        $treeNode = $this->createMock(Visitable::class);

        $map = new AvroFileMap();
        $map->set($srcFile, $treeNode);

        $this->assertEquals(1, $map->size());
    }
}
