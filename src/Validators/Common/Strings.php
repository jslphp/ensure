<?php

namespace Jsl\Ensure\Rules\Common;

use Jsl\Ensure\Rules\Contracts\RulesInterface;

class Strings implements RulesInterface
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


    /**
     * @inheritDoc
     */
    public function getRules(): array
    {
        return [
            'startsWith' => [$this, 'startsWidth'],
            'notStartsWith' => [$this, 'notStartsWidth'],
            'endsWith' => [$this, 'endsWidth'],
            'notEndsWith' => [$this, 'notEndsWidth'],
            'contains' => [$this, 'contains'],
            'notContains' => [$this, 'notContains'],
            'regex' => [$this, 'regex'],
        ];
    }


    /**
     * @inheritDoc
     */
    public function getDefaultMessages(): array
    {
        return [
            'startsWith' => '{field} must start with {a:0}',
            'notStartsWith' => '{field} must not start with {a:0}',
            'endsWith' => '{field} must end with {a:0}',
            'notEndsWith' => '{field} must not end with {a:0}',
            'contains' => '{field} must contain {a:0}',
            'notContains' => '{field} must not contain {a:0}',
            'regex' => '{field} doesn not match the required format',
        ];
    }
}
