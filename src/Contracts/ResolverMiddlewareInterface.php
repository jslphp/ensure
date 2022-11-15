<?php

namespace Jsl\Ensure\Contracts;

interface ResolverMiddlewareInterface
{
    /**
     * Resolve a class
     *
     * @param string $className
     *
     * @return object
     */
    public function resolveClass(string $className): object;
}
