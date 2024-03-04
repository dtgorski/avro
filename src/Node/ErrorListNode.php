<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Node;

use Avro\Tree\AstNode;
use Avro\Type\ErrorType;

class ErrorListNode extends AstNode
{
    public function __construct(private readonly ErrorType $type)
    {
        parent::__construct();
    }

    public function getType(): ErrorType
    {
        return $this->type;
    }
}
