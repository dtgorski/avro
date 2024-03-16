<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Render\Avdl\Handler;

use Avro\Node\RecordDeclarationNode;
use Avro\Render\Avdl\HandlerAbstract;
use Avro\Visitable;

/** @internal */
class RecordDeclarationNodeHandler extends HandlerAbstract
{
    public function canHandle(Visitable $node): bool
    {
        return $node instanceof RecordDeclarationNode;
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        if ($node instanceof RecordDeclarationNode) {
            parent::visit($node);

            $this->write($this->indent());
            $this->writeln('record ', $this->guardKeyword($node->getName()->getValue()), ' {');
            $this->stepIn();
        }
        return true;
    }

    /** @throws \Exception */
    public function leave(Visitable $node): void
    {
        if ($node instanceof RecordDeclarationNode) {
            $this->stepOut();
            $this->writeln($this->indent(), '}');

            parent::leave($node);
        }
    }
}
