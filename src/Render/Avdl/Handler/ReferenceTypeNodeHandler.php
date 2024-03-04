<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Render\Avdl\Handler;

use Avro\Node\ReferenceTypeNode;
use Avro\Render\Avdl\HandlerAbstract;
use Avro\Visitable;

/** @internal */
class ReferenceTypeNodeHandler extends HandlerAbstract
{
    public function canHandle(Visitable $node): bool
    {
        return $node instanceof ReferenceTypeNode;
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        /** @var ReferenceTypeNode $node calms static analysis down. */
        parent::visit($node);

        $this->writePropertiesSingleLine($node->getProperties());
        $this->write($node->getReference()->getQualifiedName());

        return true;
    }
}
