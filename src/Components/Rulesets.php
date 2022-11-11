<?php

namespace Jsl\Ensure\Components;

use Jsl\Ensure\Exceptions\UnknownRulesetException;

class Rulesets
{
    /**
     * @var array
     */
    protected array $rules = [];


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
        $this->rules[$name] = $rules;

        return $this;
    }


    /**
     * Check if a ruleset is added
     *
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        return key_exists($name, $this->rules);
    }


    /**
     * Get a ruleset
     *
     * @param string $name
     *
     * @return array
     */
    public function get(string $name): array
    {
        if ($this->has($name) === false) {
            throw new UnknownRulesetException("Unknown ruleset: {$name}");
        }

        return $this->rules[$name];
    }
}
