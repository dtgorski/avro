<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Render\Avdl\Handler;

use Avro\Node\EnumConstantNode;
use Avro\Render\Avdl\HandlerAbstract;
use Avro\Visitable;

/** @internal */
class EnumConstantNodeHandler extends HandlerAbstract
{
    public function canHandle(Visitable $node): bool
    {
        return $node instanceof EnumConstantNode;
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        if ($node instanceof EnumConstantNode) {
            parent::visit($node);

            $this->write($this->indent());
            $this->write($this->guardKeyword($node->getName()->getValue()));
        }
        return true;
    }

    /** @throws \Exception */
    public function leave(Visitable $node): void
    {
        if ($node instanceof EnumConstantNode) {
            if ($node->nextNode()) {
                $this->write(',');
            }
            $this->writeln();

            parent::leave($node);
        }
    }
}
