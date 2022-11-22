<?php

namespace Jsl\Ensure\Validators\Types;

use Jsl\Ensure\Abstracts\Validator;

class DecimalValidator extends Validator
{
    /**
     * @inheritDoc
     */
    protected string $template = '{field} must be a decimal';


    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function __invoke(mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE) !== null;
    }
}
