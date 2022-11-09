<?php

namespace Jsl\Ensure\Validators\Formats;

use Jsl\Ensure\Abstracts\Validator;

class EmailValidator extends Validator
{
    /**
     * @inheritDoc
     */
    protected string $message = '{field} must be a valid email address';


    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function __invoke(mixed $value): bool
    {
        return is_string($value)
            && filter_var($value, FILTER_VALIDATE_EMAIL, FILTER_NULL_ON_FAILURE) !== null;
    }
}
