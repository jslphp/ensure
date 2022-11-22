<?php

namespace Jsl\Ensure\Validators\Sizes;

use Jsl\Ensure\Abstracts\Validator;

class SizeValidator extends Validator
{
    /**
     * @inheritDoc
     */
    protected string $template = 'Size of {field} cannot be more or less than {a:0}';


    /**
     * @param mixed $value
     * @param mixed $threshold
     *
     * @return bool
     */
    public function __invoke(mixed $value, mixed $size): bool
    {
        if (is_numeric($size) === false) {
            return false;
        }

        switch (gettype($value)) {
            case 'string':
                return strlen($value) == $size;
            case 'integer':
            case 'float':
                return $value == $size;
            case 'array':
                return $value == $size;
        }

        return false;
    }
}
