<?php

namespace Jsl\Ensure\Validators\Types;

use Jsl\Ensure\Abstracts\Validator;

class StringValidator extends Validator
{
    /**
     * @inheritDoc
     */
    protected string $template = '{field} must be a string';


    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function __invoke(mixed $value): bool
    {
        return is_string($value);
    }
}
