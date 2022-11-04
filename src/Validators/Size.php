<?php

namespace Jsl\Ensure\Validators;

class Size
{
    /**
     * Check that a value has a specific size
     *
     * @param string $value
     * @param int|float $threshold
     *
     * @return bool|string
     */
    public function size(mixed $value, int|float $threshold): bool|string
    {
        switch (gettype($value)) {
            case 'string':
                return strlen($value) == $threshold
                    ?: "String lengt must be {$threshold}";
            case 'integer':
            case 'float':
                return $value == $threshold
                    ?: "Value must be {$threshold}";
            case 'array':
                return $value == $threshold
                    ?: "List must have {$threshold} item(s)";
            default:
                return 'Invalid value';
        }
    }


    /**
     * Check that a value has a minimum size
     *
     * @param string $value
     * @param int|float $threshold
     *
     * @return bool|string
     */
    public function minSize(mixed $value, int|float $threshold): bool|string
    {
        switch (gettype($value)) {
            case 'string':
                return strlen($value) >= $threshold
                    ?: "String length must be at least {$threshold}";
            case 'integer':
            case 'float':
                return $value >= $threshold
                    ?: "Value must be at least {$threshold}";
            case 'array':
                return $value >= $threshold
                    ?: "List must contain at least {$threshold} items";
            default:
                return 'Invalid value';
        }
    }


    /**
     * Check that a value has a maximum size
     *
     * @param string $value
     * @param int|float $threshold
     *
     * @return bool|string
     */
    public function maxSize(mixed $value, int|float $threshold): bool|string
    {
        switch (gettype($value)) {
            case 'string':
                return strlen($value) <= $threshold
                    ?: "String can not be longer than {$threshold}";
            case 'integer':
            case 'float':
                return $value <= $threshold
                    ?: "Value can not be more than {$threshold}";
            case 'array':
                return $value <= $threshold
                    ?: "List cannot contain more than {$threshold} items";
            default:
                return 'Invalid value';
        }
    }
}
