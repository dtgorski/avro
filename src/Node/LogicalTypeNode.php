<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Node;

use Avro\Tree\AstNode;
use Avro\Tree\Properties;
use Avro\Type\LogicalType;

class LogicalTypeNode extends AstNode
{
    public function __construct(
        private readonly LogicalType $type,
        ?Properties $properties = null
    ) {
        parent::__construct($properties);
    }

    public function getType(): LogicalType
    {
        return $this->type;
    }
}
