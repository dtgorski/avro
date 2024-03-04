<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Render\Json\Handler;

use Avro\Node\JsonFieldNode;
use Avro\Render\Json\HandlerAbstract;
use Avro\Visitable;

/** @internal */
class JsonFieldNodeHandler extends HandlerAbstract
{
    public function canHandle(Visitable $node): bool
    {
        return $node instanceof JsonFieldNode;
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        /** @var JsonFieldNode $node calms static analysis down. */
        parent::visit($node);

        if ($node->prevNode()) {
            $this->write(', ');
        }
        $this->write('"', $node->getName()->getValue(), '":');

        return true;
    }
}
