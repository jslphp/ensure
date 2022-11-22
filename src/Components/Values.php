<?php

namespace Jsl\Ensure\Components;

use InvalidArgumentException;

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
     * Add the field separator (for multidimensional arrays)
     *
     * @param string $separator
     *
     * @return self
     */
    public function setFieldSeparator(string $separator): self
    {
        $this->separator = $separator;

        return $this;
    }


    /**
     * Check if a key exists
     *
     * @param string $fieldKey
     *
     * @return bool
     */
    public function has(string $fieldKey): bool
    {
        $values = &$this->values;

        foreach (explode($this->separator, $fieldKey) as $key) {
            if (is_array($values) === false || key_exists($key, $values) === false) {
                return false;
            }

            $values = &$values[$key];
        }

        return true;
    }


    /**
     * Get a value
     *
     * @param string $fieldKey
     * @param mixed $fallback
     *
     * @return bool
     */
    public function get(string $fieldKey, mixed $fallback = null): mixed
    {
        $values = &$this->values;

        foreach (explode($this->separator, $fieldKey) as $key) {
            if (is_array($values) === false || key_exists($key, $values) === false) {
                return $fallback;
            }

            $values = &$values[$key];
        }

        return $values;
    }


    /**
     * Set a field value
     *
     * @param string $fieldKey
     * @param mixed $value
     *
     * @return self
     */
    public function set(string $fieldKey, mixed $value): self
    {
        $this->values[$fieldKey] = $value;

        return $this;
    }


    /**
     * Replace
     *
     * @param array $values
     *
     * @return self
     */
    public function replace(array $values): self
    {
        $this->values = $values;

        return $this;
    }


    /**
     * Check if a value is null
     *
     * @param string $fieldKey
     *
     * @return bool True if the value exists and is null
     */
    public function isNull(string $fieldKey): bool
    {
        return is_null($this->get($fieldKey, 'not-null'));
    }
}
