<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Render\Avdl\Handler;

use Avro\Node\EnumDeclarationNode;
use Avro\Render\Avdl\HandlerAbstract;
use Avro\Visitable;

/** @internal */
class EnumDeclarationNodeHandler extends HandlerAbstract
{
    public function canHandle(Visitable $node): bool
    {
        return $node instanceof EnumDeclarationNode;
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        if ($node instanceof EnumDeclarationNode) {
            parent::visit($node);

            $this->write($this->indent());
            $this->writeln('enum ', $this->guardKeyword($node->getName()->getValue()), ' {');
            $this->stepIn();
        }
        return true;
    }

    /** @throws \Exception */
    public function leave(Visitable $node): void
    {
        if ($node instanceof EnumDeclarationNode) {
            $this->stepOut();

            if ($node->getDefault() != '') {
                $this->writeln($this->indent(), '} = ', $node->getDefault(), ';');
            } else {
                $this->writeln($this->indent(), '}');
            }

            parent::leave($node);
        }
    }
}
