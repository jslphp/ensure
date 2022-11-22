<?php

namespace Jsl\Ensure\Validators\Types;

use Jsl\Ensure\Abstracts\Validator;

class BoolValidator extends Validator
{
    /**
     * @inheritDoc
     */
    protected string $template = '{field} must be a boolean';


    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function __invoke(mixed $value): bool
    {
        if (in_array($value, ['', null], true)) {
            // The filter accepts those values as booleans, which we don't want
            return false;
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) !== null;
    }
}
