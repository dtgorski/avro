<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Render\Avdl\Handler;

use Avro\Node\UnionTypeNode;
use Avro\Render\Avdl\HandlerAbstract;
use Avro\Visitable;

/** @internal */
class UnionTypeNodeHandler extends HandlerAbstract
{
    public function canHandle(Visitable $node): bool
    {
        return $node instanceof UnionTypeNode;
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        /** @var UnionTypeNode $node calms static analysis down. */
        parent::visit($node);

        $this->write('union {');

        if ($node->nodeCount() > 0) {
            $this->write(' ');
        }

        return true;
    }

    /** @throws \Exception */
    public function leave(Visitable $node): void
    {
        $this->write(' }');

        /** @var UnionTypeNode $node calms static analysis down. */
        parent::leave($node);
    }
}
