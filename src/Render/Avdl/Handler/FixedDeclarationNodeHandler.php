<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Render\Avdl\Handler;

use Avro\Node\FixedDeclarationNode;
use Avro\Render\Avdl\HandlerAbstract;
use Avro\Visitable;

/** @internal */
class FixedDeclarationNodeHandler extends HandlerAbstract
{
    public function canHandle(Visitable $node): bool
    {
        return $node instanceof FixedDeclarationNode;
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        /** @var FixedDeclarationNode $node calms static analysis down. */
        parent::visit($node);

        $this->write($this->indent());
        $this->write('fixed ', $this->guardKeyword($node->getName()->getValue()));
        $this->writeln('(', $node->getValue(), ');');

        return false;
    }
}
