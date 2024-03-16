<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Render\Avdl\Handler;

use Avro\Node\DecimalTypeNode;
use Avro\Render\Avdl\HandlerAbstract;
use Avro\Visitable;

/** @internal */
class DecimalTypeNodeHandler extends HandlerAbstract
{
    public function canHandle(Visitable $node): bool
    {
        return $node instanceof DecimalTypeNode;
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        if ($node instanceof DecimalTypeNode) {
            parent::visit($node);

            $this->writePropertiesSingleLine($node->getProperties());
            $this->write('decimal(', $node->getPrecision(), ', ', $node->getScale(), ')');
        }
        return false;
    }
}
