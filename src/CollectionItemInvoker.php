<?php

namespace Embed\Sample;

use BEAR\Resource\InvokerInterface;
use BEAR\Resource\ResourceObject;
use BEAR\Resource\AbstractRequest;

final class CollectionItemInvoker implements InvokerInterface {
    /**
     * @var string
     */
    private $rel;
    
    /**
     * @var ResourceObject
     */
    private $template;
    
    /**
     * @var ItemResourceGenerator valueGenerator
     */
    private $valueGenerator;
    
    public function __construct($rel, ResourceObject $template, ItemResourceGenerator $valueGenerator) {
        $this->rel = $rel;
        $this->template = $template;
        $this->valueGenerator = $valueGenerator;
    }
    
    public function invoke(AbstractRequest $request) {
        return new CollectionResourceObject(clone $this->template->uri, $this->valueGenerator->newGenerator($this->rel, $this->template));
    }
}

final class CollectionResourceObject extends ResourceObject {
    /**
     * @var Generator
     */
    private $generator;
    
    public function __construct($uri, $generator) {
        $this->uri = $uri;
        
        $body = [];
        foreach ($generator as $ro) {
            $body[] = json_decode((string)$ro, true);
        }
        $this->body = $body;
    }
    
    public function onGet() { return $this; }
    public function onPost() { return $this; }
    public function onPut() { return $this; }
    public function onDelete() { return $this; }
}
