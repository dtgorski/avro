<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Parse;

use Avro\AvroName;
use Avro\AvroNamespace;
use Avro\AvroReference;
use Avro\Node\ArrayTypeNode;
use Avro\Node\DecimalTypeNode;
use Avro\Node\DeclarationNode;
use Avro\Node\EnumConstantNode;
use Avro\Node\EnumDeclarationNode;
use Avro\Node\ErrorDeclarationNode;
use Avro\Node\ErrorListNode;
use Avro\Node\FieldDeclarationNode;
use Avro\Node\FixedDeclarationNode;
use Avro\Node\FormalParameterNode;
use Avro\Node\FormalParametersNode;
use Avro\Node\ImportStatementNode;
use Avro\Node\JsonNode;
use Avro\Node\LogicalTypeNode;
use Avro\Node\MapTypeNode;
use Avro\Node\MessageDeclarationNode;
use Avro\Node\OnewayStatementNode;
use Avro\Node\PrimitiveTypeNode;
use Avro\Node\ProtocolDeclarationNode;
use Avro\Node\RecordDeclarationNode;
use Avro\Node\ReferenceTypeNode;
use Avro\Node\ResultTypeNode;
use Avro\Node\TypeNode;
use Avro\Node\UnionTypeNode;
use Avro\Node\VariableDeclaratorNode;
use Avro\Tree\Comments;
use Avro\Tree\Node;
use Avro\Tree\Properties;
use Avro\Tree\Property;
use Avro\Type\ErrorType;
use Avro\Type\ImportType;
use Avro\Type\LogicalType;
use Avro\Type\NamedType;
use Avro\Type\PrimitiveType;

/**
 * ADVL (IDL) -> IDL AST
 * @internal
 */
class AvdlParser extends JsonParser
{
    /**
     * ProtocolDeclaration <EOF>
     *
     * @return Node
     * @throws \Exception
     */
    public function parse(): Node
    {
        $node = $this->parseProtocolDeclaration();
        $this->consume(Token::EOF);

        return $node;
    }

    /**
     * ( Property )* "protocol" Identifier ProtocolBody
     *
     * @return Node
     * @throws \Exception
     */
    protected function parseProtocolDeclaration(): Node
    {
        $propertyBag = $this->parsePropertiesWithNamespace();

        $this->consumeWithHint(Token::IDENT, self::hintProtocolKeyword, 'protocol');

        $ident = $this->parseAnyIdentifierWithHint(self::hintProtocolIdentifier);
        $name = AvroName::fromString($ident);

        $node = new ProtocolDeclarationNode($name, $propertyBag->getProperties());
        $node->setNamespace($propertyBag->getNamespace());
        $node->setComments($this->drainCommentStack());

        return $node->addNode(...$this->parseProtocolBody($propertyBag->getNamespace()));
    }

    /**
     * "{" ( Imports | Options )*  "}"
     *
     * @return Node[]
     * @throws \Exception
     */
    protected function parseProtocolBody(AvroNamespace $namespace): array
    {
        $nodes = [];
        $this->consumeWithHint(Token::LBRACE, self::hintProtocolBodyOpen);

        while (!$this->expect(Token::RBRACE)) {
            if ($this->expect(Token::IDENT, 'import')) {
                $nodes[] = $this->parseImportStatement();
                continue;
            }

            /** @var DeclarationNode $declaration calms static analysis down. */
            $declaration = $this->parseDeclaration();

            if ($declaration->getNamespace()->isEmpty()) {
                $declaration->setNamespace($namespace);
            }
            $nodes[] = $declaration;
        }

        $this->consumeWithHint(Token::RBRACE, self::hintProtocolBodyClose);
        return $nodes;
    }

    /**
     * ( ImportIdl | ImportProtocol | ImportSchema )*
     *
     * @return Node
     * @throws \Exception
     */
    protected function parseImportStatement(): Node
    {
        $types = ImportType::values();
        $this->consume(Token::IDENT, 'import');
        $type = $this->consumeWithHint(Token::IDENT, self::hintImportTypeName, ...$types)->getLoad();
        $path = $this->consumeWithHint(Token::STRING, self::hintImportFilePath)->getLoad();
        $this->parseSemicolon();

        return new ImportStatementNode(ImportType::from($type), $path);
    }

    /**
     * ( Property )* ( NamedDeclaration | MessageDeclaration ) )*
     *
     * @return Node
     * @throws \Exception
     */
    protected function parseDeclaration(): Node
    {
        $propertyBag = $this->parsePropertiesWithNamespace();

        /** @var DeclarationNode $declaration calms static analysis down, */
        $declaration = $this->expect(Token::IDENT, ...NamedType::values())
            ? $this->parseNamedDeclaration($propertyBag->getProperties())
            : $this->parseMessageDeclaration($propertyBag->getProperties());

        $declaration->setNamespace($propertyBag->getNamespace());

        return $declaration;
    }

    /**
     * ResultType Identifier FormalParameters ( "oneway" | "throws" ErrorList )? ";"
     *
     * @param Properties $properties
     * @return Node
     * @throws \Exception
     */
    protected function parseMessageDeclaration(Properties $properties): Node
    {
        $type = $this->parseResultType();
        $name = AvroName::fromString($this->parseAnyIdentifier());
        $node = new MessageDeclarationNode($name, $properties);
        $node->addNode($type, $this->parseFormalParameters());
        $node->setComments($this->drainCommentStack());

        if ($this->expect(Token::IDENT, 'throws')) {
            $node->addNode($this->parseErrorList());
        } elseif ($this->expect(Token::IDENT, 'oneway')) {
            $node->addNode($this->parseOnewayStatement());
        }
        $this->parseSemicolon();
        return $node;
    }

    /**
     *    ( "(" ( FormalParameter ( "," FormalParameter )* )? ")" )
     *
     * @return Node
     * @throws \Exception
     */
    protected function parseFormalParameters(): Node
    {
        $node = new FormalParametersNode();
        $this->consume(Token::LPAREN);

        if (!$this->expect(Token::RPAREN)) {
            $node->addNode($this->parseFormalParameter());
            while ($this->expect(Token::COMMA)) {
                $this->consume(Token::COMMA);
                $node->addNode($this->parseFormalParameter());
            }
        }
        $this->consume(Token::RPAREN);
        return $node;
    }

    /**
     * Type VariableDeclarator
     *
     * @return Node
     * @throws \Exception
     */
    protected function parseFormalParameter(): Node
    {
        $node = new FormalParameterNode();
        $type = $this->parseType();
        $node->addNode($type);
        $node->addNode($this->parseVariableDeclarator($type));
        return $node;
    }

    /**
     * ReferenceType ( "," ReferenceType )*
     *
     * @return Node
     * @throws \Exception
     */
    protected function parseErrorList(): Node
    {
        $token = $this->consume(Token::IDENT, ...ErrorType::values());
        $node = new ErrorListNode(ErrorType::from($token->getLoad()));
        $node->addNode((new TypeNode())->addNode($this->parseReferenceType(Properties::fromEmpty())));

        while ($this->expect(Token::COMMA)) {
            $this->consume(Token::COMMA);
            $node->addNode((new TypeNode())->addNode($this->parseReferenceType(Properties::fromEmpty())));
        }
        return $node;
    }

    /**
     * "oneway"
     *
     * @return Node
     * @throws \Exception
     */
    protected function parseOnewayStatement(): Node
    {
        $this->consume(Token::IDENT, 'oneway');
        return (new TypeNode())->addNode(new OnewayStatementNode());
    }

    /**
     * ( RecordDeclaration | ErrorDeclaration | EnumDeclaration | FixedDeclaration )
     *
     * @param Properties $properties
     * @return Node
     * @throws \Exception
     */
    protected function parseNamedDeclaration(Properties $properties): Node
    {
        if ($this->expect(Token::IDENT, 'error')) {
            return $this->parseErrorDeclaration($properties);
        }
        if ($this->expect(Token::IDENT, 'enum')) {
            return $this->parseEnumDeclaration($properties);
        }
        if ($this->expect(Token::IDENT, 'fixed')) {
            return $this->parseFixedDeclaration($properties);
        }
        return $this->parseRecordDeclaration($properties);
    }

    /**
     * "fixed" Identifier "(" <INTEGER> ")" ";"
     *
     * @param Properties $properties
     * @return Node
     * @throws \Exception
     */
    protected function parseFixedDeclaration(Properties $properties): Node
    {
        $this->consume(Token::IDENT, 'fixed');
        $name = AvroName::fromString($this->parseAnyIdentifier());
        $this->consume(Token::LPAREN);
        $value = $this->consume(Token::NUMBER)->getLoad();
        $node = new FixedDeclarationNode($name, (int) $value, $properties);
        $node->setComments($this->drainCommentStack());
        $this->consume(Token::RPAREN);
        $this->parseSemicolon();
        return $node;
    }

    /**
     * "record" Identifier "{" (FieldDeclaration)* "}"
     *
     * @param Properties $properties
     * @return Node
     * @throws \Exception
     */
    protected function parseRecordDeclaration(Properties $properties): Node
    {
        $this->consume(Token::IDENT, 'record');

        $ident = $this->parseAnyIdentifier();
        $name = AvroName::fromString($ident);

        $node = new RecordDeclarationNode($name, $properties);
        $node->setComments($this->drainCommentStack());
        $this->consume(Token::LBRACE);

        while (!$this->expect(Token::RBRACE)) {
            $node->addNode($this->parseFieldDeclaration());
        }
        $this->consume(Token::RBRACE);
        return $node;
    }

    /**
     * "error" Identifier "{" (FieldDeclaration)* "}"
     *
     * @param Properties $properties
     * @return Node
     * @throws \Exception
     */
    protected function parseErrorDeclaration(Properties $properties): Node
    {
        $this->consume(Token::IDENT, 'error');

        $ident = $this->parseAnyIdentifier();
        $name = AvroName::fromString($ident);

        $node = new ErrorDeclarationNode($name, $properties);
        $node->setComments($this->drainCommentStack());
        $this->consume(Token::LBRACE);

        while (!$this->expect(Token::RBRACE)) {
            $node->addNode($this->parseFieldDeclaration());
        }
        $this->consume(Token::RBRACE);
        return $node;
    }

    /**
     * "enum" Identifier "{" EnumBody "}" ( <EQ> Identifier )
     *
     * @param Properties $properties
     * @return Node
     * @throws \Exception
     */
    protected function parseEnumDeclaration(Properties $properties): Node
    {
        $default = '';
        $this->consume(Token::IDENT, 'enum');
        $ident = AvroName::fromString($this->parseAnyIdentifier());
        $this->consume(Token::LBRACE);
        $body = $this->parseEnumBody();
        $this->consume(Token::RBRACE);

        if ($this->expect(Token::EQ)) {
            $this->consume(Token::EQ);
            $default = $this->parseAnyIdentifier();

            // FIXME: check if default key exists.

            $this->parseSemicolon();
        }

        $node = new EnumDeclarationNode($ident, $default, $properties);
        $node->setComments($this->drainCommentStack());

        return $node->addNode(...$body);
    }

    /**
     * ( Identifier ( "," Identifier )* )?
     *
     * @return Node[]
     * @throws \Exception
     */
    protected function parseEnumBody(): array
    {
        $nodes = [];
        if ($this->expect(Token::IDENT) || $this->expect(Token::TICK)) {
            $name = AvroName::fromString($this->parseAnyIdentifier());
            $nodes[] = new EnumConstantNode($name);

            while ($this->expect(Token::COMMA)) {
                $this->consume(Token::COMMA);

                $name = AvroName::fromString($this->parseAnyIdentifier());
                $nodes[] = new EnumConstantNode($name);
            }
        }
        return $nodes;
    }

    /**
     * ( ( Property )* Type VariableDeclarator ( "," VariableDeclarator )* ";" )*
     *
     * @return Node
     * @throws \Exception
     */
    protected function parseFieldDeclaration(): Node
    {
        $node = new FieldDeclarationNode();
        $type = $this->parseType();

        $node->addNode($type);
        $node->addNode($this->parseVariableDeclarator($type));
        $node->setComments($this->drainCommentStack());

        while ($this->expect(Token::COMMA)) {
            $this->consume(Token::COMMA);
            $node->addNode($this->parseVariableDeclarator($type));
        }
        $this->parseSemicolon();
        return $node;
    }

    protected function ensureDefaultValueMatchesType(JsonNode $json, Node $type): void
    {
        // FIXME: implement.
    }

    /**
     * ( Property )* Identifier ( <EQ> JSONValue )?
     *
     * @param Node $type
     * @return Node
     * @throws \Exception
     */
    protected function parseVariableDeclarator(Node $type): Node
    {
        $props = $this->parsePropertiesSkipNamespace();
        $ident = $this->parseAnyIdentifier();
        $name = AvroName::fromString($ident);
        $node = new VariableDeclaratorNode($name, $props);

        if ($this->expect(Token::EQ)) {
            $this->consume(Token::EQ);

            /** @var JsonNode $json calms static analysis down. */
            $json = parent::parseJson();
            $this->ensureDefaultValueMatchesType($json, $type);

            $node->addNode($json);
        }
        return $node;
    }

    /**
     * "void" | Type
     *
     * @return Node
     * @throws \Exception
     */
    protected function parseResultType(): Node
    {
        if ($this->expect(Token::IDENT, 'void')) {
            $this->consume(Token::IDENT);
            return new ResultTypeNode(true);
        }
        return (new ResultTypeNode(false))->addNode($this->parseType());
    }

    /**
     * ( Property )* ( ReferenceType | PrimitiveType | UnionType | ArrayType | MapType | DecimalType ) "?"?
     *
     * @return Node
     * @throws \Exception
     */
    protected function parseType(): Node
    {
        $properties = $this->parsePropertiesSkipNamespace();

        $node = $this->parsePrimitiveType($properties);
        $node = $node ?? $this->parseUnionType($properties);
        $node = $node ?? $this->parseArrayType($properties);
        $node = $node ?? $this->parseMapType($properties);
        $node = $node ?? $this->parseDecimalType($properties);
        $node = $node ?? $this->parseReferenceType($properties);

        // FIXME: check properties
        if ($this->expect(Token::QMARK)) {
            $this->consume(Token::QMARK);
            $type = new TypeNode(true);
        } else {
            $type = new TypeNode();
        }
        return $type->addNode($node);
    }

    /**
     * "boolean" | "bytes" | "int" | "string" | "float" | ...
     *
     * @param Properties $properties
     * @return Node|null
     * @throws \Exception
     */
    protected function parsePrimitiveType(Properties $properties): Node|null
    {
        if ($this->expect(Token::IDENT, ...LogicalType::values())) {
            return new LogicalTypeNode(LogicalType::from($this->parseIdentifier()), $properties);
        }
        if ($this->expect(Token::IDENT, ...PrimitiveType::values())) {
            return new PrimitiveTypeNode(PrimitiveType::from($this->parseIdentifier()), $properties);
        }
        return null;
    }

    /**
     * "decimal" "(" <INTEGER>, <INTEGER> ")"
     *
     * @param Properties $properties
     * @return Node|null
     * @throws \Exception
     */
    protected function parseDecimalType(Properties $properties): Node|null
    {
        if (!$this->expect(Token::IDENT, "decimal")) {
            return null;
        }
        $this->consume(Token::IDENT);
        $this->consume(Token::LPAREN);
        $precToken = $this->peek();
        $precision = (int) $this->consume(Token::NUMBER)->getLoad();
        $this->consume(Token::COMMA);
        $scaleToken = $this->peek();
        $scale = (int) $this->consume(Token::NUMBER)->getLoad();
        $this->consume(Token::RPAREN);

        if ($precision < 0) {
            $this->throwException($precToken, 'unexpected negative decimal type precision');
        }
        if ($scale < 0 || $scale > $precision) {
            $this->throwException($scaleToken, 'unexpected invalid decimal type scale');
        }
        return new DecimalTypeNode($precision, $scale, $properties);
    }

    /**
     * "array" "<" Type ">"
     *
     * @param Properties $properties
     * @return Node|null
     * @throws \Exception
     */
    protected function parseArrayType(Properties $properties): Node|null
    {
        if (!$this->expect(Token::IDENT, 'array')) {
            return null;
        }
        $this->consume(Token::IDENT);
        $this->consume(Token::LT);
        $node = (new ArrayTypeNode($properties))->addNode($this->parseType());
        $this->consume(Token::GT);
        return $node;
    }

    /**
     * "map" "<" Type ">"
     *
     * @param Properties $properties
     * @return Node|null
     * @throws \Exception
     */
    protected function parseMapType(Properties $properties): Node|null
    {
        if (!$this->expect(Token::IDENT, 'map')) {
            return null;
        }
        $this->consume(Token::IDENT);
        $this->consume(Token::LT);
        $node = (new MapTypeNode($properties))->addNode($this->parseType());
        $this->consume(Token::GT);
        return $node;
    }

    /**
     * "union" "{" Type ( "," Type )* "}"
     *
     * @param Properties $properties
     * @return Node|null
     * @throws \Exception
     */
    protected function parseUnionType(Properties $properties): Node|null
    {
        if (!$this->expect(Token::IDENT, 'union')) {
            return null;
        }
        $this->consume(Token::IDENT);
        $this->consume(Token::LBRACE);
        $node = (new UnionTypeNode($properties))->addNode($this->parseType());

        while ($this->expect(Token::COMMA)) {
            $this->consume(Token::COMMA);
            $node->addNode($this->parseType());
        }
        $this->consume(Token::RBRACE);
        return $node;
    }

    /**
     * ( Identifier ( "." Identifier )* )
     *
     * @param Properties $properties
     * @return Node
     * @throws \Exception
     */
    protected function parseReferenceType(Properties $properties): Node
    {
        $parts = [];
        $parts[] = $this->parseAnyIdentifierWithHint(self::hintReferenceIdentifier);

        while ($this->expect(Token::DOT)) {
            $this->consume(Token::DOT);
            $parts[] = $this->parseAnyIdentifierWithHint(self::hintReferenceIdentifier);
        }

        $name = join('.', $parts);
        return new ReferenceTypeNode(AvroReference::fromString($name), $properties);
    }

    /**
     * PropertyName "(" JSONValue ")"
     *
     * @return Property
     * @throws \Exception
     */
    protected function parseProperty(): Property
    {
        $name = $this->parsePropertyName();
        $this->consume(Token::LPAREN);

        /** @var mixed $json */
        $json = json_decode(json_encode(parent::parseJson()));

        $this->consume(Token::RPAREN);
        return new Property($name, $json);
    }

    /**
     * ( "@" PropertyName "(" JSONValue ")" )*
     *
     * @return Properties
     * @throws \Exception
     */
    protected function parsePropertiesSkipNamespace(): Properties
    {
        $props = [];
        while ($this->expect(Token::AT)) {
            $this->consume(Token::AT);
            $property = $this->parseProperty();

            if ($property->getName() != 'namespace') {
                $props[] = $property;
            }
        }
        return Properties::fromArray($props);
    }

    /**
     * ( "@" PropertyName "(" JSONValue ")" )*
     *
     * @return PropertiesWithNamespace
     * @throws \Exception
     */
    protected function parsePropertiesWithNamespace(): PropertiesWithNamespace
    {
        $properties = [];
        $namespace = '';

        while ($this->expect(Token::AT)) {
            $this->consume(Token::AT);
            $token = $this->peek();
            $property = $this->parseProperty();

            if ($property->getName() != 'namespace') {
                $properties[] = $property;
                continue;
            }
            if (is_string($property->getJson())) {
                $namespace = (string) $property->getJson();
                continue;
            }
            $this->throwUnexpectedTokenWithHint(
                $token,
                self::hintProtocolNamespace
            );
        }
        return new PropertiesWithNamespace(
            Properties::fromArray($properties),
            AvroNamespace::fromString($namespace)
        );
    }

    /**
     * <IDENTIFIER> (<DASH> <IDENTIFIER>)*
     *
     * @return string
     * @throws \Exception
     */
    protected function parsePropertyName(): string
    {
        $ident = $this->parseAnyIdentifier();
        while ($this->expect(Token::DASH)) {
            $this->consume(Token::DASH);
            $ident = $ident . '-' . $this->parseAnyIdentifier();
        }
        return $ident;
    }

    /**
     * @param string $hint Informative part for a possible error message.
     * @return string
     * @throws \Exception
     */
    protected function parseIdentifierWithHint(string $hint): string
    {
        return $this->consumeWithHint(Token::IDENT, $hint)->getLoad();
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function parseIdentifier(): string
    {
        return $this->parseIdentifierWithHint('<identifier>');
    }

    /**
     * @param string $hint Informative part for a possible error message.
     * @return string
     * @throws \Exception
     */
    protected function parseAnyIdentifierWithHint(string $hint): string
    {
        if ($this->expect(Token::TICK)) {
            $this->consume(Token::TICK);
            $ident = $this->parseIdentifierWithHint($hint);
            $this->consume(Token::TICK);
            return $ident;
        }
        return $this->parseIdentifierWithHint($hint);
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function parseAnyIdentifier(): string
    {
        if ($this->expect(Token::TICK)) {
            $this->consume(Token::TICK);
            $ident = $this->parseIdentifier();
            $this->consume(Token::TICK);
            return $ident;
        }
        return $this->parseIdentifier();
    }

    /** @throws \Exception */
    protected function parseSemicolon(): void
    {
        $this->consumeWithHint(Token::SEMICOL, self::hintTrailingSemicolon);
    }

    /** @return Comments */
    private function drainCommentStack(): Comments
    {
        return Comments::fromArray($this->getCursor()->getCommentStack()->drain());
    }

    // @formatter:off
    // phpcs:disable
    private const
        // Message "expected ..."
        hintProtocolNamespace   = "@namespace(...) property value to be string",
        hintProtocolKeyword     = "@namespace(...) property and 'protocol' keyword",
        hintProtocolIdentifier  = "protocol name <identifier>",
        hintProtocolBodyOpen    = "protocol body opening brace '{'",
        hintProtocolBodyClose   = "protocol body closing brace '}'",
        hintImportFilePath      = "import file path in double quotes",
        hintTrailingSemicolon   = "trailing semicolon ';'",
        hintReferenceIdentifier = "reference type name <identifier>",

        // TODO: implement protocol and schema imports.
        #hintImportTypeName     = "import type to be one of 'idl', 'protocol' or 'schema'",
        hintImportTypeName      = "import type to be 'idl' ('protocol' or 'schema' unsupported)"
    ;
    // phpcs:enable
    // @formatter:on
}

/** @codeCoverageIgnore */
// phpcs:disable
class PropertiesWithNamespace
{
    public function __construct(
        private readonly Properties $properties,
        private readonly AvroNamespace $namespace
    ) {
    }

    public function getProperties(): Properties
    {
        return $this->properties;
    }

    public function getNamespace(): AvroNamespace
    {
        return $this->namespace;
    }
}
