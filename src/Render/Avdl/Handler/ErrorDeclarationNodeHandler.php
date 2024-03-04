<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Render\Avdl\Handler;

use Avro\Node\ErrorDeclarationNode;
use Avro\Render\Avdl\HandlerAbstract;
use Avro\Visitable;

/** @internal */
class ErrorDeclarationNodeHandler extends HandlerAbstract
{
    public function canHandle(Visitable $node): bool
    {
        return $node instanceof ErrorDeclarationNode;
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        /** @var ErrorDeclarationNode $node calms static analysis down. */
        parent::visit($node);

        $this->write($this->indent());
        $this->writeln('error ', $this->guardKeyword($node->getName()->getValue()), ' {');

        $this->stepIn();

        return true;
    }

    /** @throws \Exception */
    public function leave(Visitable $node): void
    {
        $this->stepOut();

        /** @var ErrorDeclarationNode $node calms static analysis down. */
        $this->writeln($this->indent(), '}');

        parent::leave($node);
    }
}
