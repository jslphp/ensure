<?php

namespace Jsl\Ensure\Validators;

use Jsl\Ensure\Validators\Traits\RequireValuesTrait;

class Comparisons
{
    use RequireValuesTrait;


    /**
     * Check that two fields contains the same value
     *
     * @param string $value
     * @param string $field
     *
     * @return bool
     */
    public function same(string $value, string $field): bool
    {
        return $value === $this->values->get($field);
    }


    /**
     * Check that two fields does not contain the same value
     *
     * @param string $value
     * @param string $field
     *
     * @return bool
     */
    public function notSame(string $value, string $field): bool
    {
        return $value !== $this->values->get($field);
    }
}
