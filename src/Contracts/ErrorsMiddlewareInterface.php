<?php

namespace Jsl\Ensure\Contracts;

use Jsl\Ensure\Components\Field;

interface ErrorsMiddlewareInterface
{
    /**
     * Render errors from the failed rules
     *
     * @param Field $field
     * @param array $failedRules
     *
     * @return string|array
     */
    public function renderErrors(Field $field, array $failedRules): string|array;
}
