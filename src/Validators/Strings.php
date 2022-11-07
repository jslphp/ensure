<?php

namespace Jsl\Ensure\Validators;

class Strings
{
    /**
     * Check if a value starts with a specific string
     *
     * @param mixed $value
     * @param string $text
     *
     * @return bool
     */
    public function startsWith(mixed $value, string $text): bool
    {
        return is_string($value) && is_string($text) && str_starts_with($value, $text);
    }


    /**
     * Check if a value does not starts with a specific string
     *
     * @param mixed $value
     * @param string $text
     *
     * @return bool
     */
    public function notStartsWith(mixed $value, string $text): bool
    {
        return !is_string($value) || !is_string($text) || !str_starts_with($value, $text);
    }


    /**
     * Check if a value ends with a specific string
     *
     * @param mixed $value
     * @param string $text
     *
     * @return bool
     */
    public function endsWith(mixed $value, string $text): bool
    {
        return is_string($value) && is_string($text) && str_ends_with($value, $text);
    }


    /**
     * Check if a value does not end with a specific string
     *
     * @param mixed $value
     * @param string $text
     *
     * @return bool
     */
    public function notEndsWith(mixed $value, string $text): bool
    {
        return !is_string($value) || !is_string($text) || !str_ends_with($value, $text);
    }


    /**
     * Check if a value contains a specific string
     *
     * @param mixed $value
     * @param string $text
     *
     * @return bool
     */
    public function contains(mixed $value, string $text): bool
    {
        return is_string($value) && is_string($text) && str_contains($value, $text);
    }


    /**
     * Check if a value does not contain a specific string
     *
     * @param mixed $value
     * @param string $text
     *
     * @return bool
     */
    public function notContains(mixed $value, string $text): bool
    {
        return !is_string($value) || !is_string($text) || !str_contains($value, $text);
    }


    /**
     * Validate against a regular expression
     *
     * @param mixed $value
     * @param string $expression
     *
     * @return bool
     */
    public function regex(mixed $value, string $expression): bool
    {
        return is_string($value) && preg_match($expression, $value) === 1;
    }
}
