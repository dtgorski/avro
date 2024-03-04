<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Render\Diag;

use Avro\Node\DecimalTypeNode;
use Avro\Node\DeclarationNode;
use Avro\Node\JsonNode;
use Avro\Node\LogicalTypeNode;
use Avro\Node\MessageDeclarationNode;
use Avro\Node\PrimitiveTypeNode;
use Avro\Node\RecordDeclarationNode;
use Avro\Node\ReferenceTypeNode;
use Avro\Node\ResultTypeNode;
use Avro\Node\TypeNode;
use Avro\Node\VariableDeclaratorNode;
use Avro\Tree\AstNode;
use Avro\Tree\Node;
use Avro\Visitable;
use Avro\Visitor;
use Avro\Write\StandardWriter;
use Avro\Write\Writer;

/** @internal */
class AstDumper implements Visitor
{
    public function __construct(
        private readonly Writer $writer = new StandardWriter()
    ) {
    }

    /** @throws \Exception */
    public function visit(Visitable $node): bool
    {
        $writer = $this->writer;

        /** @var AstNode $node calms static analysis down. */
        $parts = explode('\\', get_class($node));
        $name = $parts[sizeof($parts) - 1];
        $edges = $this->edges($node);

        $line = sprintf('%s%s', $edges, $name);
        $len1 = strlen($line);
        $len2 = strlen(utf8_decode($line));
        $line = substr($line . str_repeat(' ', 56), 0, 56 - ($len2 - $len1));

        $nullableType = function (Node $node): string {
            /** @var TypeNode $type calms static analysis down. */
            $type = $node->parentNode();
            return $type->isNullable() ? '?' : '';
        };

        $writer->write($line, ':');

        if ($node instanceof DeclarationNode && !$node->getNamespace()->isEmpty()) {
            $writer->write(' @namespace("', $node->getNamespace()->getValue(), '")');
        }

        switch (true) {
            case $node instanceof DecimalTypeNode:
                $writer->write(' (', $node->getPrecision(), ', ', $node->getScale(), ')');
                break;
            case $node instanceof JsonNode:
                $writer->write(' ', json_encode($node));
                break;
            case $node instanceof LogicalTypeNode:
                $writer->write(' ', $node->getType()->value);
                break;
            case $node instanceof MessageDeclarationNode:
                $writer->write(' ', $node->getName()->getValue());
                break;
            case $node instanceof PrimitiveTypeNode:
                $writer->write(' ', $node->getType()->value);
                $writer->write($nullableType($node));
                break;
            case $node instanceof RecordDeclarationNode:
                $writer->write(' ', $node->getName()->getValue());
                break;
            case $node instanceof ReferenceTypeNode:
                $writer->write(' ', $node->getReference()->getQualifiedName());
                $writer->write($nullableType($node));
                break;
            case $node instanceof ResultTypeNode && $node->isVoid():
                $writer->write(' void');
                break;
            case $node instanceof VariableDeclaratorNode:
                $writer->write(' ', $node->getName()->getValue());
                break;
        }

        if (!$node->getProperties()->isEmpty()) {
            $writer->write(' @', trim(json_encode($node->getProperties()->asArray())));
        }
        $this->writer->write("\n");

        return true;
    }

    /** @throws \Exception */
    public function leave(Visitable $node): void
    {
    }

    private function edges(Node $node): string
    {
        $edge = $node->parentNode() ? ($node->nextNode() ? '├── ' : '└── ') : '';
        while ($node = $node->parentNode()) {
            $edge = sprintf('%s%s', ($node->nextNode() ? '│   ' : '    '), $edge);
        }
        return $edge;
    }
}
