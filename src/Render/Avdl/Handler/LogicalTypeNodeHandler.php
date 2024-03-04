<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Render\Avdl\Handler;

use Avro\Node\LogicalTypeNode;
use Avro\Render\Avdl\HandlerAbstract;
use Avro\Visitable;

/** @internal */
class LogicalTypeNodeHandler extends HandlerAbstract
{
    public function canHandle(Visitable $node): bool
    {
        return $node instanceof LogicalTypeNode;
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        /** @var LogicalTypeNode $node calms static analysis down. */
        parent::visit($node);

        $this->writePropertiesSingleLine($node->getProperties());
        $this->write($node->getType()->value);

        return false;
    }
}
