<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Parse;

use Avro\AvroName;
use Avro\Node\JsonArrayNode;
use Avro\Node\JsonFieldNode;
use Avro\Node\JsonObjectNode;
use Avro\Node\JsonValueNode;
use Avro\Tree\Node;

/**
 * JSON -> JSON AST
 * @internal
 */
class JsonParser extends ParserBase
{
    /**
     * @return Node
     * @throws \Exception
     */
    public function parse(): Node
    {
        return $this->parseJson();
    }

    /**
     * @return Node
     * @throws \Exception
     */
    protected function parseJson(): Node
    {
        // @formatter:off
        // phpcs:disable
        switch (($token = $this->peek())->getType()) {
            case Token::LBRACK: return $this->parseJsonArray();
            case Token::LBRACE: return $this->parseJsonObject();
            case Token::STRING: return $this->parseJsonString();
            case Token::NUMBER: return $this->parseJsonNumber();
            case Token::IDENT:
                switch ($token->getLoad()) {
                    case 'true':
                    case 'false': return $this->parseJsonBool();
                    case 'null':  return $this->parseJsonNull();
                    default:      $this->throwUnexpectedTokenWithHint($token, 'valid JSON');
                }
            default: $this->throwUnexpectedToken($token);
        }
        // phpcs:enable
        // @formatter:on
    }

    /**
     * @return Node
     * @throws \Exception
     */
    protected function parseJsonString(): Node
    {
        $token = $this->consume(Token::STRING);
        return new JsonValueNode($token->getLoad(), $token->getPosition());
    }

    /**
     * @return Node
     * @throws \Exception
     */
    protected function parseJsonNumber(): Node
    {
        $token = $this->consume(Token::NUMBER);
        return new JsonValueNode((float) $token->getLoad(), $token->getPosition());
    }

    /**
     * @return Node
     * @throws \Exception
     */
    protected function parseJsonBool(): Node
    {
        $token = $this->consume(Token::IDENT, 'true', 'false');
        return new JsonValueNode($token->getLoad() === 'true', $token->getPosition());
    }

    /**
     * @return Node
     * @throws \Exception
     */
    protected function parseJsonNull(): Node
    {
        $token = $this->consume(Token::IDENT, 'null');
        return new JsonValueNode(null, $token->getPosition());
    }

    /**
     * @return Node
     * @throws \Exception
     */
    protected function parseJsonArray(): Node
    {
        $token = $this->consume(Token::LBRACK);
        $node = new JsonArrayNode($token->getPosition());

        if (!$this->expect(Token::RBRACK)) {
            $node->addNode($this->parseJson());

            while ($this->expect(Token::COMMA)) {
                $this->consume(Token::COMMA);
                $node->addNode($this->parseJson());
            }
        }
        $this->consume(Token::RBRACK);
        return $node;
    }

    /**
     * @return Node
     * @throws \Exception
     */
    protected function parseJsonObject(): Node
    {
        $token = $this->consume(Token::LBRACE);
        $node = new JsonObjectNode($token->getPosition());

        if (!$this->expect(Token::RBRACE)) {
            $node->addNode($this->parseJsonField());

            while ($this->expect(Token::COMMA)) {
                $this->consume(Token::COMMA);
                $node->addNode($this->parseJsonField());
            }
        }
        $this->consume(Token::RBRACE);
        return $node;
    }

    /**
     * @return Node
     * @throws \Exception
     */
    protected function parseJsonField(): Node
    {
        $token = $this->consume(Token::STRING);
        $node = new JsonFieldNode(AvroName::fromString($token->getLoad()), $token->getPosition());
        $this->consume(Token::COLON);
        return $node->addNode($this->parseJson());
    }
}
