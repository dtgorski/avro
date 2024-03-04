<?php

// MIT License · Daniel T. Gorski · dtg [at] lengo [dot] org · 02/2024

declare(strict_types=1);

namespace Avro\Parse;

use Avro\Tree\Node;

/** @internal */
interface Parser
{
    /** @throws \Exception */
    public function parse(): Node;
}
