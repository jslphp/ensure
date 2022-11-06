<?php

namespace Jsl\Ensure\Validators;

class Arrays
{
    /**
     * Check that a value exists in a list of values
     *
     * @param string $value
     * @param array ...$list
     *
     * @return bool|string
     */
    public function in(string $value, ...$list): bool|string
    {
        return in_array($value, $list)
            ?: 'Value not accepted';
    }


    /**
     * Check that a value does not exist in a list of values
     *
     * @param string $value
     * @param array ...$list
     *
     * @return bool|string
     */
    public function notIn(string $value, ...$list): bool|string
    {
        return in_array($value, $list) === false
            ?: 'Value not accepted';
    }
}
