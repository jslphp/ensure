<?php

namespace Jsl\Ensure\Validators;

class Types
{
    /**
     * Check if a value is a string
     *
     * @param mixed $value
     *
     * @return bool|string
     */
    public function isString(mixed $value): bool|string
    {
        return is_string($value)
            ?: 'Must be a string';
    }


    /**
     * Check if a value is an integer
     *
     * @param mixed $value
     *
     * @return bool|string
     */
    public function isInt(mixed $value): bool|string
    {
        return filter_var($value, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE)
            ?: 'Must be an integer';
    }


    /**
     * Check if a value is numeric
     *
     * @param mixed $value
     *
     * @return bool|string
     */
    public function isNumeric(mixed $value): bool|string
    {
        return is_numeric($value)
            ?: 'Must be a number';
    }


    /**
     * Check if a value is a float
     *
     * @param mixed $value
     *
     * @return bool|string
     */
    public function isFloat(mixed $value): bool|string
    {
        return filter_var($value, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE)
            ?: 'Must be a decimal';
    }


    /**
     * Check if a value is an array
     *
     * @param mixed $value
     *
     * @return bool|string
     */
    public function isArray(mixed $value): bool|string
    {
        return is_array($value)
            ?: 'Must be an array';
    }


    /**
     * Check if a value is a boolean
     *
     * @param mixed $value
     *
     * @return bool|string
     */
    public function isBool(mixed $value): bool|string
    {
        return filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE)
            ?: 'Must be a boolean';
    }
}
