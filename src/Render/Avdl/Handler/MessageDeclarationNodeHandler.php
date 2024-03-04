<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Render\Avdl\Handler;

use Avro\Node\MessageDeclarationNode;
use Avro\Render\Avdl\HandlerAbstract;
use Avro\Visitable;

/** @internal */
class MessageDeclarationNodeHandler extends HandlerAbstract
{
    public function canHandle(Visitable $node): bool
    {
        return $node instanceof MessageDeclarationNode;
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        /** @var MessageDeclarationNode $node calms static analysis down. */
        parent::visit($node);

        $this->write($this->indent());

        return true;
    }

    /** @throws \Exception */
    public function leave(Visitable $node): void
    {
        $this->writeln(';');

        /** @var MessageDeclarationNode $node calms static analysis down. */
        parent::leave($node);
    }
}
