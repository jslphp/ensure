<?php

namespace Jsl\Ensure\Validators\Types;

use Jsl\Ensure\Abstracts\Validator;

class ArrayValidator extends Validator
{
    /**
     * @inheritDoc
     */
    protected string $template = '{field} must be an array';


    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function __invoke(mixed $value): bool
    {
        return is_array($value);
    }
}
