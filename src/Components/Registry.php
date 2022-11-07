<?php

namespace Jsl\Ensure\Components;

use Closure;
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
     * @var Closure
     */
    protected Closure $resolver;


    /**
     *
     * @param Closure|null $resolver Function for instantiating class instances
     */
    public function __construct(?Closure $resolver = null)
    {
        $this->resolver = $resolver ?? function ($className) {
            return new $className;
        };
    }


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
     * @param array $validators Format: [validatorName => callable|[classname|instance, method]]
     *
     * @return self
     */
    public function addBatch(array $validators): self
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

        $callback = $this->index[$name];

        if ($this->needResolving($callback)) {
            $callback[0] = $this->resolver->call($this, $callback[0]);
        }

        if ($values && $this->needValues($callback)) {
            $callback[0]->setValues($values);
        }

        return $callback;
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

        return $callback instanceof Closure && $values
            ? $callback->call($values, $args)
            : call_user_func_array($callback, $args);
    }


    /**
     * Check if the method needs to be resolved
     *
     * @param mixed $callback
     *
     * @return bool
     */
    protected function needResolving($callback): bool
    {
        return is_array($callback)
            && count($callback) == 2
            && is_string($callback[0])
            && key_exists($callback[0], $this->instances) === false;
    }


    /**
     * Inject values to the callback instance, if all conditions are met
     *
     * @param mixed $callback
     *
     * @return bool
     */
    protected function needValues(mixed $callback): bool
    {
        return is_array($callback)
            && is_object($callback[0])
            && method_exists($callback[0], 'setValues');
    }
}
