<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Render\Avdl\Handler;

use Avro\Node\OnewayStatementNode;
use Avro\Render\Avdl\HandlerAbstract;
use Avro\Visitable;

/** @internal */
class OnewayStatementNodeHandler extends HandlerAbstract
{
    public function canHandle(Visitable $node): bool
    {
        return $node instanceof OnewayStatementNode;
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        /** @var OnewayStatementNode $node calms static analysis down. */
        parent::visit($node);

        $this->write(' oneway');

        return false;
    }
}
