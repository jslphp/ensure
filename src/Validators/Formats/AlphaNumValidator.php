<?php

namespace Jsl\Ensure\Validators\Formats;

use Jsl\Ensure\Abstracts\Validator;

class AlphaNumValidator extends Validator
{
    /**
     * @inheritDoc
     */
    protected string $message = '{field} can only contain alpha numeric characters';


    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function __invoke(mixed $value): bool
    {
        return is_string($value) && ctype_alnum($value);
    }
}
