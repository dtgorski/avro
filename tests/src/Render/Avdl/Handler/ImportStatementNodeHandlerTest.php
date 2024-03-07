<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Tests\Render\Avdl\Handler;

use Avro\AvroName;
use Avro\Node\ImportStatementNode;
use Avro\Node\ProtocolDeclarationNode;
use Avro\Tests\AvroTestCase;
use Avro\Type\ImportType;
use Avro\Render\Avdl\Handler\ImportStatementNodeHandler;
use Avro\Render\Avdl\HandlerContext;
use Avro\Write\BufferedWriter;

/**
 * @covers \Avro\Render\Avdl\Handler\ImportStatementNodeHandler
 * @uses   \Avro\AvroName
 * @uses   \Avro\AvroNamespace
 * @uses   \Avro\Node\DeclarationNode
 * @uses   \Avro\Node\ImportStatementNode
 * @uses   \Avro\Node\NamedDeclarationNode
 * @uses   \Avro\Node\ProtocolDeclarationNode
 * @uses   \Avro\Render\Avdl\HandlerAbstract
 * @uses   \Avro\Render\Avdl\HandlerContext
 * @uses   \Avro\Shared\EntityMap
 * @uses   \Avro\Tree\AstNode
 * @uses   \Avro\Tree\Comments
 * @uses   \Avro\Tree\Properties
 * @uses   \Avro\Tree\TreeNode
 * @uses   \Avro\Write\BufferedWriter
 */
class ImportStatementNodeHandlerTest extends AvroTestCase
{
    public function testVisitWithoutSiblingNodes(): void
    {
        $node = new ImportStatementNode(ImportType::IDL, 'foo');
        $writer = new BufferedWriter();
        $handler = new ImportStatementNodeHandler(new HandlerContext($writer));
        $handler->visit($node);

        $this->assertTrue($handler->canHandle($node));
        $this->assertEquals("\nimport idl \"foo\";\n", $writer->getBuffer());
    }

    public function testVisitWithSiblingNodes(): void
    {
        $node = new ProtocolDeclarationNode(AvroName::fromString('proto'));
        $node->addNode(new ImportStatementNode(ImportType::IDL, 'foo'));
        $node->addNode(new ImportStatementNode(ImportType::IDL, 'bar'));

        $writer = new BufferedWriter();
        $handler = new ImportStatementNodeHandler(new HandlerContext($writer));

        /** @psalm-suppress PossiblyNullArgument */
        $handler->visit($node->nodeAt(0));

        /** @psalm-suppress PossiblyNullArgument */
        $handler->visit($node->nodeAt(1));

        $this->assertEquals("\nimport idl \"foo\";\nimport idl \"bar\";\n", $writer->getBuffer());
    }
}
