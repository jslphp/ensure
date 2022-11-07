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
     * @return bool
     */
    public function in(string $value, ...$list): bool
    {
        return in_array($value, $list);
    }


    /**
     * Check that a value does not exist in a list of values
     *
     * @param string $value
     * @param array ...$list
     *
     * @return bool
     */
    public function notIn(string $value, ...$list): bool
    {
        return in_array($value, $list) === false;
    }
}
