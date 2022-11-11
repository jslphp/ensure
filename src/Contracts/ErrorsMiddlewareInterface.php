<?php

namespace Jsl\Ensure\Contracts;

interface ErrorsMiddlewareInterface
{
    /**
     * Render errors from the failed rules
     *
     * @param string $fieldName
     * @param array $failedRules
     *
     * @return string|array
     */
    public function renderErrors(string $fieldName, array $failedRules): string|array;
}
