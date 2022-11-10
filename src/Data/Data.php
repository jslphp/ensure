<?php

namespace Jsl\Ensure\Data;

class Data
{
    /**
     * @var array
     */
    protected array $data;

    /**
     * @var string
     */
    protected string $separator;


    /**
     * @param array $data
     * @param string $separator
     */
    public function __construct(array $data, string $separator = '.')
    {
        $this->data = $data;
        $this->separator = $separator;
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
        $data = &$this->data;

        foreach (explode($this->separator, $fieldKey) as $key) {
            if (is_array($data) === false || key_exists($key, $data) === false) {
                return false;
            }

            $data = &$data[$key];
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
        $data = &$this->data;

        foreach (explode($this->separator, $fieldKey) as $key) {
            if (is_array($data) === false || key_exists($key, $data) === false) {
                return $fallback;
            }

            $data = &$data[$key];
        }

        return $data;
    }
}
