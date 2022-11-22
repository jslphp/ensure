<?php

namespace Jsl\Ensure\Validators\Arrays;

use Jsl\Ensure\Abstracts\Validator;

class InValidator extends Validator
{
    /**
     * @inheritDoc
     */
    protected string $template = '{field} contains an unaccepted value';


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

        if (count($haystack) === 1 && is_array($haystack[0])) {
            $haystack = $haystack[0];
        }

        return in_array($value, $haystack);
    }
}
