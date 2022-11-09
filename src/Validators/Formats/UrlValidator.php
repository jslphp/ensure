<?php

namespace Jsl\Ensure\Validators\Formats;

use Jsl\Ensure\Abstracts\Validator;

class UrlValidator extends Validator
{
    /**
     * @inheritDoc
     */
    protected string $message = '{field} must a valid URL';


    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function __invoke(mixed $value): bool
    {
        return is_string($value)
            && filter_var($value, FILTER_VALIDATE_URL, FILTER_NULL_ON_FAILURE) !== null;
    }
}
