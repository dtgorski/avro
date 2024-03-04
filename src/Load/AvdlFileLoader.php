<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Load;

use Avro\AvroFileMap;
use Avro\AvroFilePath;
use Avro\Node\DeclarationNode;
use Avro\Node\ImportStatementNode;
use Avro\Parse\AvdlParser;
use Avro\Parse\ByteStreamReader;
use Avro\Parse\CommentSaveCursor;
use Avro\Parse\Lexer;
use Avro\Tree\Node;
use Avro\Type\ImportType;
use Avro\Render\Walker;

/**
 * ADVL (IDL) -> AST
 * @internal
 */
class AvdlFileLoader extends FileLoader
{
    public function __construct(
        private readonly AvroFileMap $fileMap = new AvroFileMap()
    ) {
    }

    /** @throws \Exception */
    public function load(AvroFilePath $filePath): AvroFileMap
    {
        if ($this->fileMap->has($filePath)) {
            return $this->fileMap;
        }

        $stream = $this->open($filePath);

        try {
            /** @var DeclarationNode $node */
            $node = $this->parseProtocol($stream);
            $this->fileMap->set($filePath, $node);

            $this->resolveImports($filePath, $node);

        } catch (\Exception $e) {
            $this->throwWithFileName($e, $filePath);

        } finally {
            $this->close($stream);
        }

        return $this->fileMap;
    }

    /**
     * @param resource $stream
     * @throws \Exception
     */
    protected function parseProtocol($stream): Node
    {
        $tokens = (new Lexer())->createTokenStream(new ByteStreamReader($stream));
        $cursor = new CommentSaveCursor($tokens);
        return (new AvdlParser($cursor))->parse();
    }

    /**
     * @throws \Exception
     * @codeCoverageIgnore
     */
    protected function resolveImports(AvroFilePath $file, Node $node): void
    {
        $imports = function (Node $node) use ($file): bool {
            if (!$node instanceof ImportStatementNode) {
                return true;
            }
            if ($node->getType() !== ImportType::IDL) {
                throw new \Exception(
                    sprintf("unsupported import type '%s'", $node->getType()->value)
                );
            }

            $filename = sprintf('%s/%s', $file->getDirname(), $node->getPath());
            $sourceFile = AvroFilePath::fromString($filename);

            // Attention: recursion.
            $this->load($sourceFile);

            return true;
        };

        Walker::fromFunc($imports)->traverseNode($node);
    }
}
