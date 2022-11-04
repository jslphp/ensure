<?php

namespace Jsl\Ensure\Components;

class Values
{
    /**
     * @var array
     */
    protected array $values;

    /**
     * @var string
     */
    protected string $separator;


    /**
     * @param array $values
     * @param string $separator
     */
    public function __construct(array $values, string $separator = '.')
    {
        $this->values = $values;
        $this->separator = $separator;
    }


    /**
     * Check if a key exists
     *
     * @param string $field
     *
     * @return bool
     */
    public function has(string $field): bool
    {
        $values = &$this->values;

        foreach (explode($this->separator, $field) as $key) {
            if (!is_array($values) || !array_key_exists($key, $values)) {
                return false;
            }

            $values = &$values[$key];
        }

        return true;
    }


    /**
     * Get a value
     *
     * @param string $field
     * @param mixed $fallback
     *
     * @return mixed
     */
    public function get(string $field, mixed $fallback = null): mixed
    {
        $values = &$this->values;

        foreach (explode($this->separator, $field) as $key) {
            if (!is_array($values) || !array_key_exists($key, $values)) {
                return $fallback;
            }

            $values = &$values[$key];
        }

        return $values;
    }
}
