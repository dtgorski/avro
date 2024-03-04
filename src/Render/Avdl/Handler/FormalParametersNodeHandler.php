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
        /** @var FormalParametersNode $node calms static analysis down. */
        parent::visit($node);

        /** @var MessageDeclarationNode $message calms static analysis down. */
        $message = $node->parentNode();

        $this->write(' ', $this->guardKeyword($message->getName()->getValue()), '(');

        return true;
    }

    /** @throws \Exception */
    public function leave(Visitable $node): void
    {
        $this->write(')');

        /** @var FormalParametersNode $node calms static analysis down. */
        parent::leave($node);
    }
}
