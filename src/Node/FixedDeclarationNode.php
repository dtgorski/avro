<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Node;

use Avro\AvroName;
use Avro\Tree\Properties;

class FixedDeclarationNode extends NamedDeclarationNode
{
    public function __construct(
        AvroName $name,
        private readonly int $value,
        ?Properties $properties = null
    ) {
        parent::__construct($name, $properties);
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
