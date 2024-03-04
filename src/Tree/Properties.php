<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tree;

use Avro\Shared\EntityMap;

/** @extends EntityMap<int, Property> */
class Properties extends EntityMap implements \JsonSerializable
{
    public function getByName(string $name): Property|null
    {
        foreach ($this->asArray() as $property) {
            if ($name === $property->getName()) {
                return $property;
            }
        }
        return null;
    }

    public function jsonSerialize(): object
    {
        return (object)$this->asArray();
    }
}
