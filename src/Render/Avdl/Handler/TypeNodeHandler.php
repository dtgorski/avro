<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Render\Avdl\Handler;

use Avro\Node\DeclarationNode;
use Avro\Node\OnewayStatementNode;
use Avro\Node\TypeNode;
use Avro\Render\Avdl\HandlerAbstract;
use Avro\Visitable;

/** @internal */
class TypeNodeHandler extends HandlerAbstract
{
    public function canHandle(Visitable $node): bool
    {
        return $node instanceof TypeNode;
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        /** @var TypeNode $node calms static analysis down. */
        parent::visit($node);

        if ($node->parentNode() instanceof DeclarationNode) {
            if (!$node->nodeAt(0) instanceof OnewayStatementNode) {
                $this->write($this->indent());
            }
        } else {
            $this->writePropertiesSingleLine($node->getProperties());
        }

        return true;
    }

    /** @throws \Exception */
    public function leave(Visitable $node): void
    {
        /** @var TypeNode $node calms static analysis down. */
        if ($node->isNullable()) {
            $this->write('?');
        }

        if ($node->nextNode() instanceof TypeNode) {
            $this->write(', ');
        }

        parent::leave($node);
    }
}
