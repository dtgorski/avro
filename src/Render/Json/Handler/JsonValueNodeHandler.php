<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Render\Json\Handler;

use Avro\Node\JsonValueNode;
use Avro\Render\Json\HandlerAbstract;
use Avro\Visitable;

/** @internal */
class JsonValueNodeHandler extends HandlerAbstract
{
    public function canHandle(Visitable $node): bool
    {
        return $node instanceof JsonValueNode;
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        /** @var JsonValueNode $node calms static analysis down. */
        parent::visit($node);

        if ($node->prevNode()) {
            $this->write(', ');
        }
        $val = $node->getValue();

        switch (true) {
            case is_float($val):
                $this->write(json_encode($val));
                break;
            case is_string($val):
                $this->write($this->escapeJson($val));
                break;
            case $val === null:
                $this->write('null');
                break;
            case $val === true:
                $this->write('true');
                break;
            case $val === false:
                $this->write('false');
                break;
        }
        return false;
    }

    private function escapeJson(string $json): string
    {
        return json_encode($json);
    }
}
