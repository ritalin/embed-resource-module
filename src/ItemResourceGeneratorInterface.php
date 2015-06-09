<?php

namespace Embed\Sample;

use BEAR\Resource\ResourceObject;

interface ItemResourceGeneratorInterface {
    /**
     * @param ResourceObject template
     * @return Generator
     */
    function newGenerator($rel, ResourceObject $template);
}
