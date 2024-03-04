<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Render\Avdl\Handler;

use Avro\Node\ArrayTypeNode;
use Avro\Render\Avdl\HandlerAbstract;
use Avro\Visitable;

/** @internal */
class ArrayTypeNodeHandler extends HandlerAbstract
{
    public function canHandle(Visitable $node): bool
    {
        return $node instanceof ArrayTypeNode;
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        parent::visit($node);

        $this->write('array<');

        return true;
    }

    /** @throws \Exception */
    public function leave(Visitable $node): void
    {
        $this->write('>');

        parent::leave($node);
    }
}
