<?php

namespace Embed\Sample\Module;

use Ray\Di\AbstractModule;

use Embed\Sample\Annotation\DirectEmbed;
use Embed\Sample\Interceptor\CollectionItemResourceResolver;

class AppModule extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->bindInterceptor(
            $this->matcher->any(),
            $this->matcher->annotatedWith(DirectEmbed::class),
            [CollectionItemResourceResolver::class]
        );
    }
}
