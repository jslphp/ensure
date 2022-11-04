<?php

namespace Jsl\Ensure\Validators;

use Jsl\Ensure\Components\Values;

class Comparisons
{
    /**
     * @var Values
     */
    protected Values $values;


    /**
     * Check that two fields contains the same value
     *
     * @param string $value
     * @param string $field
     *
     * @return bool|string
     */
    public function same(string $value, string $field): bool|string
    {
        return $value === $this->values->get($field)
            ?: "Must be same as {$field}";
    }


    /**
     * Check that two fields does not contain the same value
     *
     * @param string $value
     * @param string $field
     *
     * @return bool|string
     */
    public function notSame(string $value, string $field): bool|string
    {
        return $value !== $this->values->get($field)
            ?: "Must not be same as {$field}";
    }
}
