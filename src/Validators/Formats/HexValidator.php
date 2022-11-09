<?php

namespace Jsl\Ensure\Validators\Formats;

use Jsl\Ensure\Abstracts\Validator;

class HexValidator extends Validator
{
    /**
     * @inheritDoc
     */
    protected string $message = '{field} must contain a hexa decimal value';


    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function __invoke(mixed $value): bool
    {
        return is_string($value) && ctype_xdigit($value);
    }
}
