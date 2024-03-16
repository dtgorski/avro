<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Render\Avdl\Handler;

use Avro\Node\ImportStatementNode;
use Avro\Render\Avdl\HandlerAbstract;
use Avro\Visitable;

/** @internal */
class ImportStatementNodeHandler extends HandlerAbstract
{
    public function canHandle(Visitable $node): bool
    {
        return $node instanceof ImportStatementNode;
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        if ($node instanceof ImportStatementNode) {
            parent::visit($node);

            if (!$node->prevNode() instanceof ImportStatementNode) {
                $this->writeln();
            }
            $name = $node->getType()->value;
            $path = $node->getPath();
            $this->writeln($this->indent(), 'import ', $name, ' "', $path, '";');

            return false;
        }
        return true;
    }
}
