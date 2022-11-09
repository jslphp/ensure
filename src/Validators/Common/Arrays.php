<?php

namespace Jsl\Ensure\Rules\Common;

use Jsl\Ensure\Rules\Contracts\RulesInterface;

class Arrays implements RulesInterface
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


    /**
     * @inheritDoc
     */
    public function getRules(): array
    {
        return [
            'in'    => [$this, 'in'],
            'notIn' => [$this, 'notIn'],
        ];
    }


    /**
     * @inheritDoc
     */
    public function getDefaultMessages(): array
    {
        return [
            'in'    => 'Value of {field} is not allowed',
            'notIn' => 'Value of {field} is not allowed',
        ];
    }
}
