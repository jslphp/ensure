<?php

namespace Jsl\Ensure\Middlewares;

use Jsl\Ensure\Contracts\ResolverMiddlewareInterface;

class ResolverMiddleware implements ResolverMiddlewareInterface
{
    /**
     * @inheritDoc
     */
    public function resolveClass(string $className): object
    {
        return new $className;
    }
}
