<?php

namespace Jsl\Ensure\Components;

use Jsl\Ensure\Exceptions\UnknownValidatorException;

class Registry
{
    /**
     * @var array
     */
    protected array $index = [];

    /**
     * @var array
     */
    protected array $instances = [];


    /**
     * Add a validator
     *
     * @param string $name
     * @param array|callable $validator
     *
     * @return self
     */
    public function add(string $name, array|callable $validator): self
    {
        $this->index[$name] = $validator;

        return $this;
    }


    /**
     * Add list of validators
     *
     * @param array $validators
     *
     * @return self
     */
    public function batchAdd(array $validators): self
    {
        foreach ($validators as $name => $validator) {
            $this->add($name, $validator);
        }

        return $this;
    }


    /**
     * Check if a validator is registered
     *
     * @param string $name
     *
     * @return bool
     */
    public function has($name): bool
    {
        return key_exists($name, $this->index);
    }


    /**
     * Get and resolve a callback
     *
     * @param string $name
     * @param Values|null $values
     *
     * @return callable
     */
    public function get(string $name, ?Values $values = null): mixed
    {
        if ($this->has($name) === false) {
            throw new UnknownValidatorException("Call to unknown validator {$name}");
        }

        $cb = $this->index[$name];

        // If the callback is an array with [classname, methodname], we need to check 
        // if we should instantiate it and/or pass the values
        if (is_array($cb) && count($cb) === 2) {
            if (is_string($cb[0]) && key_exists($cb[0], $this->instances) === false) {
                // It's not instantiated, so lets do that and store the instance in
                // the cache for quich reuse
                $cb[0] = $this->index[$name][0] = $this->instances[$cb[0]] = new $cb[0];
            }

            if ($values && method_exists($cb[0], 'setValues')) {
                // The object has a setValues method, so let's pass the values to it
                $cb[0]->setValues($values);
            }
        }

        return $cb;
    }


    /**
     * Execute a rule
     *
     * @param string $name
     * @param array $args
     * @param Values|null $values
     *
     * @return bool|string
     */
    public function execute(string $name, array $args, ?Values $values = null): bool|string
    {
        $callback = $this->get($name, $values);

        return call_user_func_array($callback, $args);
    }
}
