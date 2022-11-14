<?php

namespace Jsl\Ensure\Validators\Arrays;

use Jsl\Ensure\Abstracts\Validator;

class InValidator extends Validator
{
    /**
     * @inheritDoc
     */
    protected string $message = '{field} contains an unaccepted value';


    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function __invoke(mixed $value, ...$haystack): bool
    {
        if (is_string($value) === false && is_numeric($value) === false) {
            return false;
        }

        return in_array($value, $haystack);
    }
}
