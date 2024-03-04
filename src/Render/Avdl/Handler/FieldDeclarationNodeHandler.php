<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Render\Avdl\Handler;

use Avro\Node\FieldDeclarationNode;
use Avro\Render\Avdl\HandlerAbstract;
use Avro\Visitable;

/** @internal */
class FieldDeclarationNodeHandler extends HandlerAbstract
{
    public function canHandle(Visitable $node): bool
    {
        return $node instanceof FieldDeclarationNode;
    }

    /** @throws \Exception */
    public function leave(Visitable $node): void
    {
        $this->writeln(';');

        /** @var FieldDeclarationNode $node calms static analysis down. */
        parent::leave($node);
    }
}
