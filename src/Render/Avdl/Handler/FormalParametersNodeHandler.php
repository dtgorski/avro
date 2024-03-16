<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Render\Avdl\Handler;

use Avro\Node\FormalParametersNode;
use Avro\Node\MessageDeclarationNode;
use Avro\Render\Avdl\HandlerAbstract;
use Avro\Visitable;

/** @internal */
class FormalParametersNodeHandler extends HandlerAbstract
{
    public function canHandle(Visitable $node): bool
    {
        return $node instanceof FormalParametersNode;
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        if ($node instanceof FormalParametersNode) {
            parent::visit($node);

            if (($message = $node->parentNode()) && $message instanceof MessageDeclarationNode) {
                $this->write(' ', $this->guardKeyword($message->getName()->getValue()), '(');
            }
        }
        return true;
    }

    /** @throws \Exception */
    public function leave(Visitable $node): void
    {
        if ($node instanceof FormalParametersNode) {
            $this->write(')');

            parent::leave($node);
        }
    }
}
