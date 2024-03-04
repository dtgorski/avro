<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Node;

use Avro\Tree\AstNode;

class TypeNode extends AstNode
{
    public function __construct(private readonly bool $isNullable = false)
    {
        parent::__construct();
    }

    public function isNullable(): bool
    {
        return $this->isNullable;
    }
}
