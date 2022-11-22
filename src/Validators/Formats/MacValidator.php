<?php

namespace Jsl\Ensure\Validators\Formats;

use Jsl\Ensure\Abstracts\Validator;

class MacValidator extends Validator
{
    /**
     * @inheritDoc
     */
    protected string $template = '{field} must be a valid MAC address';


    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function __invoke(mixed $value): bool
    {
        return is_string($value)
            && filter_var($value, FILTER_VALIDATE_MAC, FILTER_NULL_ON_FAILURE) !== null;
    }
}
