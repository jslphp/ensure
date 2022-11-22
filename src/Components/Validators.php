<?php

namespace Jsl\Ensure\Components;

use Closure;
use Jsl\Ensure\Contracts\ValidatorInterface;
use Jsl\Ensure\Exceptions\InvalidCallbackException;
use Jsl\Ensure\Exceptions\UnknownValidatorException;

class Validators
{
    /**
     * @var array
     */
    protected array $validators;

    /**
     * @var Closure
     */
    protected Closure $classResolver;

    /**
     * @param bool $defaults
     */
    public function __construct(bool $defaults = true)
    {
        if ($defaults) {
            $this->addMany(require __DIR__ . '/../Validators/defaults.php');
        }

        // Set the default class resolver
        $this->classResolver = function (string $className): object {
            return new $className;
        };
    }


    /**
     * Set validator class resolver
     *
     * @param Closure $resolver
     *
     * @return self
     */
    public function setClassResolver(Closure $resolver): self
    {
        $this->classResolver = $resolver;

        return $this;
    }


    /**
     * Add a list of validators
     *
     * @param array $validators
     *
     * @return self
     */
    public function addMany(array $validators): self
    {
        foreach ($validators as $name => $callback) {
            $this->add($name, $callback);
        }

        return $this;
    }


    /**
     * Add a validator
     *
     * @param string $name
     * @param mixed $callback
     *
     * @return self
     * 
     * @throws InvalidCallbackException
     */
    public function add(string $name, mixed $callback): self
    {
        if ($this->isValidCallback($callback) === false) {
            throw new InvalidCallbackException("Invalid validator callback. Read doc for more info");
        }

        $this->validators[$name] = $callback;

        return $this;
    }



    /**
     * Get a validator
     *
     * @param string $name
     * @param Values $values
     *
     * @return mixed
     * 
     * @throws UnknownValidatorException
     */
    public function get(string $name, Values $values): mixed
    {
        if (isset($this->validators[$name]) === false) {
            throw new UnknownValidatorException("Unknown validator: {$name}");
        }

        $callback = $this->validators[$name];

        if (is_string($callback) && class_exists($callback)) {
            $callback = $this->classResolver->call($this, $callback);
        }

        if (is_array($callback) && count($callback) === 2 && is_string($callback[0])) {
            $callback[0] = $this->classResolver->call($this, $callback[0]);
        }

        if ($callback instanceof ValidatorInterface) {
            $callback->setValues($values);
        }

        return $this->validators[$name] = $callback;
    }


    /**
     * Check if a callback is valid
     *
     * @param mixed $callback
     *
     * @return bool
     */
    public function isValidCallback(mixed $callback): bool
    {
        return is_callable($callback)
            || (is_string($callback) && class_exists($callback))
            || (is_array($callback) && count($callback) === 2 && is_string($callback[0]));
    }
}
