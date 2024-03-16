<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Render\Avdl\Handler;

use Avro\Node\PrimitiveTypeNode;
use Avro\Render\Avdl\HandlerAbstract;
use Avro\Visitable;

/** @internal */
class PrimitiveTypeNodeHandler extends HandlerAbstract
{
    public function canHandle(Visitable $node): bool
    {
        return $node instanceof PrimitiveTypeNode;
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        if ($node instanceof PrimitiveTypeNode) {
            parent::visit($node);

            $this->writePropertiesSingleLine($node->getProperties());
            $this->write($node->getType()->value);

            return false;
        }
        return true;
    }
}
