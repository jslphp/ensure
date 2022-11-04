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
     * @return bool|string
     */
    public function startsWith(mixed $value, string $text): bool|string
    {
        if (is_string($value) === false) {
            return "Must be a string";
        }

        return str_starts_with($value, $text)
            ?: "Must start with {$text}";
    }


    /**
     * Check if a value does not starts with a specific string
     *
     * @param mixed $value
     * @param string $text
     *
     * @return bool|string
     */
    public function notStartsWith(mixed $value, string $text): bool|string
    {
        if (is_string($value) === false) {
            return "Must be a string";
        }

        return str_starts_with($value, $text) === false
            ?: "Must not start with {$text}";
    }


    /**
     * Check if a value ends with a specific string
     *
     * @param mixed $value
     * @param string $text
     *
     * @return bool|string
     */
    public function endsWith(mixed $value, string $text): bool|string
    {
        if (is_string($value) === false) {
            return "Must be a string";
        }

        return str_ends_with($value, $text)
            ?: "Must end with {$text}";
    }


    /**
     * Check if a value does not end with a specific string
     *
     * @param mixed $value
     * @param string $text
     *
     * @return bool|string
     */
    public function notEndsWith(mixed $value, string $text): bool|string
    {
        if (is_string($value) === false) {
            return "Must be a string";
        }

        return str_ends_with($value, $text) === false
            ?: "Must end with {$text}";
    }


    /**
     * Check if a value contains a specific string
     *
     * @param mixed $value
     * @param string $text
     *
     * @return bool|string
     */
    public function contains(mixed $value, string $text): bool|string
    {
        if (is_string($value) === false) {
            return "Must be a string";
        }

        return str_contains($value, $text)
            ?: "Must contain with {$text}";
    }


    /**
     * Check if a value does not contain a specific string
     *
     * @param mixed $value
     * @param string $text
     *
     * @return bool|string
     */
    public function notContains(mixed $value, string $text): bool|string
    {
        if (is_string($value) === false) {
            return "Must be a string";
        }

        return str_contains($value, $text)
            ?: "Must not contain {$text}";
    }


    /**
     * Validate against a regular expression
     *
     * @param mixed $value
     * @param string $expression
     *
     * @return bool|string
     */
    public function regex(mixed $value, string $expression): bool|string
    {
        if (is_string($value) === false) {
            return "Must be a string";
        }

        return preg_match($expression, $value) === 1
            ?: "Invalid format";
    }
}
