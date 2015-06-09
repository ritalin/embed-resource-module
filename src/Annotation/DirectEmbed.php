<?php

namespace Embed\Sample\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
final class DirectEmbed {
    /**
     * @var string
     */
    public $rel;
    
    /**
     * @var string
     */
    public $uri;
}