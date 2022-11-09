<?php

namespace Jsl\Ensure\Validators;

use Closure;
use Jsl\Ensure\Contracts\ValidatorInterface;
use Jsl\Ensure\Contracts\ValidatorsRegistryInterface;

class Registry implements ValidatorsRegistryInterface
{
    /**
     * @var array
     */
    protected array $resolved = [];

    /**
     * @var array
     */
    protected array $index = [];


    public function __construct()
    {
        $this->addMany(require __DIR__ . '/defaults.php');
    }


    /**
     * Add a validator
     *
     * @param string $name
     * @param string|Closure|ValidatorInterface $validator
     *
     * @return self
     */
    public function add(string $name, string|Closure|ValidatorInterface $validator): self
    {
        $this->index[$name] = $validator;

        return $this;
    }


    /**
     * Add multiple validators from an array
     *
     * @param array $validators Format: [string $name => string|Closure|ValidatorInterface $validator, ...]
     *
     * @return self
     */
    public function addMany(array $validators): self
    {
        foreach ($validators as $name => $validator) {
            $this->add($name, $validator);
        }

        return $this;
    }


    /**
     * Check if the registry has a named validator
     *
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        return key_exists($name, $this->index);
    }
}
