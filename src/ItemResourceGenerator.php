<?php

namespace Embed\Sample;

use BEAR\Resource\ResourceObject;

class ItemResourceGenerator implements ItemResourceGeneratorInterface {
    /**
     * @var array
     */
    private $values;
    
    /**
     * @var callable
     */
    private $keySelector;
    
    public function __construct(array $values, callable $keySelector) {
        $this->values = $values;
        $this->keySelector = $keySelector;
    }
    
    /**
     * {@inheritdoc}
     */
    function newGenerator($rel, ResourceObject $template) {
        $keySelector = $this->keySelector;
        $builder = $this->builder;
        
        foreach ($this->values as $v) {
            $template->uri->query = $keySelector($v);
            $template[$rel] = $v;
            
            yield $template;
        }
    }
}
