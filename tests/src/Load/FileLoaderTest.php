<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Load;

use Avro\AvroFileMap;
use Avro\AvroFilePath;
use Avro\Load\FileLoader;
use Avro\Tests\AvroTestCase;

/**
 * @covers \Avro\Load\FileLoader
 * @uses   \Avro\AvroFilePath
 */
class FileLoaderTest extends AvroTestCase
{
    public function testThrowWithFileName(): void
    {
        $fileName = AvroFilePath::fromString('foo');
        $loader = new BogusFileLoader();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessageMatches('/bar/');

        $loader->throwWithFileName(new \Exception('bar'), $fileName);
    }
}

// phpcs:ignore
class BogusFileLoader extends FileLoader
{
    public function load(AvroFilePath $filePath): AvroFileMap
    {
        return new AvroFileMap();
    }

    public function open(AvroFilePath $fileName): mixed
    {
        return parent::open($fileName);
    }

    public function close(mixed $stream): void
    {
        parent::close($stream);
    }

    public function throwWithFileName(\Exception $e, AvroFilePath $fileName): never
    {
        parent::throwWithFileName($e, $fileName);
    }
}
