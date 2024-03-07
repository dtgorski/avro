<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Node;

use Avro\AvroName;
use Avro\AvroNamespace;
use Avro\Tree\Properties;

class NamedDeclarationNode extends DeclarationNode
{
    private AvroNamespace $namespace;

    public function __construct(
        private readonly AvroName $name,
        ?Properties $properties = null
    ) {
        parent::__construct($properties);
        $this->namespace = AvroNamespace::fromString('');
    }

    public function getNamespace(): AvroNamespace
    {
        return $this->namespace;
    }

    public function setNamespace(AvroNamespace $namespace): DeclarationNode
    {
        $this->namespace = $namespace;
        return $this;
    }

    public function getName(): AvroName
    {
        return $this->name;
    }
}
