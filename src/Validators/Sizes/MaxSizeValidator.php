<?php

namespace Jsl\Ensure\Validators\Sizes;

use Jsl\Ensure\Abstracts\Validator;

class MaxSizeValidator extends Validator
{
    /**
     * @inheritDoc
     */
    protected string $message = 'Size of {field} cannot be less than {a:0}';


    /**
     * @param mixed $value
     * @param mixed $threshold;
     *
     * @return bool
     */
    public function __invoke(mixed $value, mixed $threshold): bool
    {
        if (is_numeric($threshold) === false) {
            return false;
        }

        switch (gettype($value)) {
            case 'string':
                return strlen($value) <= $threshold;
            case 'integer':
            case 'float':
                return $value <= $threshold;
            case 'array':
                return $value <= $threshold;
        }

        return false;
    }
}
