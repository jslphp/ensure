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
     * @return bool
     */
    public function size(mixed $value, int|float $size): bool
    {
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


    /**
     * Check that a value has a minimum size
     *
     * @param string $value
     * @param int|float $threshold
     *
     * @return bool
     */
    public function minSize(mixed $value, int|float $threshold): bool
    {
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


    /**
     * Check that a value has a maximum size
     *
     * @param string $value
     * @param int|float $threshold
     *
     * @return bool
     */
    public function maxSize(mixed $value, int|float $threshold): bool
    {
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
