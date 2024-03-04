<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Node;

use Avro\AvroName;
use Avro\Tree\AstNode;

class EnumConstantNode extends AstNode
{
    public function __construct(
        private readonly AvroName $name
    ) {
        parent::__construct();
    }

    public function getName(): AvroName
    {
        return $this->name;
    }
}
