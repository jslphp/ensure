<?php

namespace Jsl\Ensure\Contracts;

use Closure;
use Jsl\Ensure\Entities\CallbackEntity;

interface RegistryInterface
{
    /**
     * Add a validator
     *
     * @param string $name
     * @param string|Closure|ValidatorInterface $validator
     *
     * @return self
     */
    public function add(string $name, string|Closure|ValidatorInterface $validator): self;


    /**
     * Add multiple validators from an array
     *
     * @param array $validators Format: [$name => string|Closure|ValidatorInterface $validator][]
     *
     * @return self
     */
    public function addMany(array $validators): self;


    /**
     * Check if the registry has a named validator
     *
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool;


    /**
     * Get the callback for a named validator
     *
     * @param string $name
     *
     * @return CallbackEntity
     */
    public function get(string $name): CallbackEntity;
}
