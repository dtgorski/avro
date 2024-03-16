<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Render\Avdl\Handler;

use Avro\Node\MapTypeNode;
use Avro\Render\Avdl\HandlerAbstract;
use Avro\Visitable;

/** @internal */
class MapTypeNodeHandler extends HandlerAbstract
{
    public function canHandle(Visitable $node): bool
    {
        return $node instanceof MapTypeNode;
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        if ($node instanceof MapTypeNode) {
            parent::visit($node);

            $this->write('map<');
        }
        return true;
    }

    /** @throws \Exception */
    public function leave(Visitable $node): void
    {
        if ($node instanceof MapTypeNode) {
            $this->write('>');

            parent::leave($node);
        }
    }
}
