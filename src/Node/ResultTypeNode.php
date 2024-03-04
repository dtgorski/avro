<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Node;

use Avro\Tree\AstNode;

class ResultTypeNode extends AstNode
{
    public function __construct(private readonly bool $isVoid)
    {
        parent::__construct();
    }

    public function isVoid(): bool
    {
        return $this->isVoid;
    }
}
