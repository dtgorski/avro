<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Node;

use Avro\AvroName;
use Avro\Tree\Properties;

class ProtocolDeclarationNode extends DeclarationNode
{
    public function __construct(
        private readonly AvroName $name,
        ?Properties $properties = null
    ) {
        parent::__construct($properties);
    }

    public function getName(): AvroName
    {
        return $this->name;
    }
}
