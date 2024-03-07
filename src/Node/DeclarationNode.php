<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Node;

use Avro\AvroNamespace;
use Avro\Tree\AstNode;
use Avro\Tree\Comments;
use Avro\Tree\Properties;

abstract class DeclarationNode extends AstNode
{
    private Comments $comments;

    /** @throws \Exception */
    public function __construct(?Properties $properties = null)
    {
        parent::__construct($properties);
        $this->comments = Comments::fromArray([]);
    }

    /** @return Comments */
    public function getComments(): Comments
    {
        return $this->comments;
    }

    public function setComments(Comments $comments): DeclarationNode
    {
        $this->comments = $comments;
        return $this;
    }
}
