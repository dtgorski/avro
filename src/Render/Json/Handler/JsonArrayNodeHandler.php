<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Render\Json\Handler;

use Avro\Node\JsonArrayNode;
use Avro\Render\Json\HandlerAbstract;
use Avro\Visitable;

/** @internal */
class JsonArrayNodeHandler extends HandlerAbstract
{
    public function canHandle(Visitable $node): bool
    {
        return $node instanceof JsonArrayNode;
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        /** @var JsonArrayNode $node calms static analysis down. */
        parent::visit($node);

        if ($node->prevNode()) {
            $this->write(', ');
        }
        $this->write('[');

        return true;
    }

    /** @throws \Exception */
    public function leave(Visitable $node): void
    {
        /** @var JsonArrayNode $node calms static analysis down. */
        parent::leave($node);

        $this->write(']');
    }
}
