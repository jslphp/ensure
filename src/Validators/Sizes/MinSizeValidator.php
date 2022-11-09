<?php

namespace Jsl\Ensure\Validators\Sizes;

use Jsl\Ensure\Abstracts\Validator;

class MinSizeValidator extends Validator
{
    /**
     * @inheritDoc
     */
    protected string $message = 'Size of {field} must be at least {a:0}';


    /**
     * @param mixed $value
     * @param mixed $threshold;
     *
     * @return bool
     */
    public function __invoke(mixed $value, mixed $threshold): bool
    {
        if (is_numeric($threshold) === true) {
            return false;
        }

        switch (gettype($value)) {
            case 'string':
                return strlen($value) >= $threshold;
            case 'integer':
            case 'float':
                return $value >= $threshold;
            case 'array':
                return $value >= $threshold;
        }

        return false;
    }
}
