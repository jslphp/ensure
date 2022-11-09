<?php

namespace Jsl\Ensure\Rules\Common;

use Jsl\Ensure\Rules\Contracts\RequireValuesInterface;
use Jsl\Ensure\Rules\Contracts\RulesInterface;
use Jsl\Ensure\Rules\Traits\RequireValuesTrait;

class Comparisons implements RequireValuesInterface, RulesInterface
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


    /**
     * @inheritDoc
     */
    public function getRules(): array
    {
        return [
            'same'    => [$this, 'same'],
            'notSame' => [$this, 'notSame'],
        ];
    }


    /**
     * @inheritDoc
     */
    public function getDefaultMessages(): array
    {
        return [
            'same'    => '{field} does not match {a:0}',
            'notSame' => '{field} cannot be same as {a:0}',
        ];
    }
}
