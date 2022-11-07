<?php

namespace Jsl\Ensure\Components;

use Jsl\Ensure\Exceptions\UnknownRulesetException;

class Rulesets
{
    /**
     * @var array
     */
    protected array $rulesets = [];


    /**
     * Add a ruleset
     *
     * @param string $name
     * @param array $ruleset
     *
     * @return self
     */
    public function add(string $name, array $ruleset): self
    {
        $this->rulesets[$name] = $ruleset;

        return $this;
    }


    /**
     * Check if a ruleset exists
     *
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        return key_exists($name, $this->rulesets);
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
            throw new UnknownRulesetException("Unknown ruleset '{$name}'");
        }

        return $this->rulesets[$name];
    }
}
