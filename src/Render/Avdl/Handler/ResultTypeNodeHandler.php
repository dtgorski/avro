<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Render\Avdl\Handler;

use Avro\Node\ResultTypeNode;
use Avro\Render\Avdl\HandlerAbstract;
use Avro\Visitable;

/** @internal */
class ResultTypeNodeHandler extends HandlerAbstract
{
    public function canHandle(Visitable $node): bool
    {
        return $node instanceof ResultTypeNode;
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        if ($node instanceof ResultTypeNode) {
            parent::visit($node);

            if ($node->isVoid()) {
                $this->write('void');
                return false;
            }
        }
        return true;
    }
}
