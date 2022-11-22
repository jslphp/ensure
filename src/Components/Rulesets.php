<?php

namespace Jsl\Ensure\Components;

use Jsl\Ensure\Exceptions\UnknownRulesetException;

class Rulesets
{
    /**
     * @var array
     */
    protected array $sets = [];


    /**
     * Check if a ruleset exists
     *
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        return key_exists($name, $this->sets);
    }


    /**
     * Add a ruleset
     *
     * @param string $name
     * @param array $rules
     *
     * @return self
     */
    public function add(string $name, array $rules): self
    {
        $this->sets[$name] = $rules;

        return $this;
    }


    /**
     * Get a ruleset
     *
     * @param string $name
     *
     * @return array
     * 
     * @throws UnknownRulesetException
     */
    public function get(string $name): array
    {
        if ($this->has($name) === false) {
            throw new UnknownRulesetException("Unknown ruleset: {$name}");
        }

        return $this->sets[$name];
    }
}
