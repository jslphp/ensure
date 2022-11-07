<?php

namespace Jsl\Ensure\Validators;

class Types
{
    /**
     * Check if a value is a string
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function isString(mixed $value): bool
    {
        return is_string($value);
    }


    /**
     * Check if a value is an integer
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function isInt(mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE) !== null;
    }


    /**
     * Check if a value is numeric
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function isNumeric(mixed $value): bool
    {
        return is_numeric($value);
    }


    /**
     * Check if a value is a float
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function isFloat(mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE) !== null;
    }


    /**
     * Check if a value is an array
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function isArray(mixed $value): bool
    {
        return is_array($value);
    }


    /**
     * Check if a value is a boolean
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function isBool(mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) !== null;
    }
}
