<?php

namespace Embed\Sample\Interceptor;

use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;
use Doctrine\Common\Annotations\Reader;

use BEAR\Resource\FactoryInterface;
use BEAR\Resource\ResourceObject;
use BEAR\Resource\Request;

use Embed\Sample\Annotation\DirectEmbed;

use Embed\Sample\ItemResourceGeneratorInterface;
use Embed\Sample\CollectionItemInvoker;s

class CollectionItemResourceResolver implements MethodInterceptor {
    /**
     * @var FactoryInterface
     */
    private $reourceFactory;

    /**
     * @var Reader
     */
    private $reader;
    
    public function __construct(FactoryInterface $factory, Reader $reader) {
        $this->reourceFactory = $factory;
        $this->reader = $reader;
    }
    
    public function invoke(MethodInvocation $invocation) {
        $resourceObject = $invocation->getThis();

        $result = $invocation->proceed();

        $annotation = $this->extractAnnotation($invocation->getMethod(), DirectEmbed::class);
        if ($annotation === null) {
            throw new \Exception('Intercepter conflicted');
        }
        
        $uri = clone $resourceObject->uri;
        $uri->path = $annotation->uri;
        $rel = $annotation->rel;
        
        if ($result instanceof ResourceObject) {
            return $this->toItemResource($rel, $uri, $result);
        }
        else {
            return $result;
        }
    }
    
    private function extractAnnotation($method, $class) {
        $annotations = array_filter(
            $this->reader->getMethodAnnotations($method),
            function ($a) use($class) {
                return $a instanceof $class;
            }
        );
        
        return array_shift($annotations);
    }
    
    private function toItemResource($rel, $uri, $resourceObject) {
        return array_map(
            function ($contents) use($rel, $uri, $resourceObject) {
                if ($contents instanceof ItemResourceGeneratorInterface) {
                    $ro = $this->reourceFactory->newInstance($uri);
                    $ro->uri = $uri;
                    $invoker = new CollectionItemInvoker($rel, $ro, $contents);

                    return new Request($invoker);
                }
                else {
                    return $contents;
                }
            },
            $resourceObject->body
        );
    }
}
