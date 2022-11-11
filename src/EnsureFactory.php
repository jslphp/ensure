<?php

namespace Jsl\Ensure;

use Closure;
use Jsl\Ensure\Components\Container;
use Jsl\Ensure\Contracts\ValidatorInterface;

class EnsureFactory
{
    /**
     * @var Container
     */
    protected Container $container;


    public function __construct()
    {
        $this->container = new Container;
    }


    /**
     * Add a validator
     *
     * @param string $name
     * @param string|Closure|ValidatorInterface $validator
     *
     * @return self
     */
    public function addValidator(string $name, string|Closure|ValidatorInterface $validator): self
    {
        $this->container->validators()->add($name, $validator);

        return $this;
    }


    /**
     * Add an array of validatords
     *
     * @param array $validators
     *
     * @return self
     */
    public function addManyValidators(array $validators): self
    {
        $this->container->validators()->addMany($validators);

        return $this;
    }


    /**
     * Add a ruleset
     *
     * @param string $name
     * @param array $rules
     *
     * @return self
     */
    public function addRuleset(string $name, array $rules): self
    {
        $this->container->rulesets()->add($name, $rules);

        return $this;
    }


    /**
     * Check if a ruleset is added
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasRuleset(string $name): bool
    {
        return $this->container->rulesets()->has($name);
    }


    /**
     * Get a ruleset
     *
     * @param string $name
     *
     * @return array
     */
    public function getRuleset(string $name): array
    {
        return $this->container->rulesets()->get($name);
    }


    /**
     * Create a new Ensure instance
     *
     * @param array $data
     * @param array|string $rules
     *
     * @return Ensure
     */
    public function create(array $data, array|string $rules = []): Ensure
    {
        return new Ensure($data, $rules, $this->container);
    }
}
