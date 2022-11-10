<?php

namespace Jsl\Ensure\Entities;

use Closure;
use Jsl\Ensure\Contracts\ValidatorInterface;

class CallbackEntity
{
    /**
     * @var string
     */
    protected string $name;

    /**
     * @var string|Closure|ValidatorInterface
     */
    protected string|Closure|ValidatorInterface $validator;

    /**
     * @var array
     */
    protected array $arguments = [];


    /**
     * @param string $name
     * @param string|Closure|ValidatorInterface $validator
     * @param array $arguments
     */
    public function __construct(string $name, string|Closure|ValidatorInterface $validator, array $arguments = [])
    {
        $this->name = $name;
        $this->validator = $validator;
        $this->arguments = $arguments;
    }


    /**
     * Get the rule name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->string;
    }


    /**
     * Get the validator
     *
     * @return string|Closure|ValidatorInterface
     */
    public function getValidator(): string|Closure|ValidatorInterface
    {
        return $this->validator;
    }


    /**
     * Set the validator
     *
     * @param string|Closure|ValidatorInterface $validator
     *
     * @return self
     */
    public function setValidator(string|Closure|ValidatorInterface $validator): self
    {
        $this->validator = $validator;

        return $this;
    }


    /**
     * Get the arguments
     *
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }


    /**
     * Set the arguments
     *
     * @param array $arguments
     *
     * @return self
     */
    public function setArguments(array $arguments): self
    {
        $this->arguments = $arguments;

        return $this;
    }


    /**
     * Get the validator message
     *
     * @param string $fallback
     *
     * @return string
     */
    public function getMessage(string $fallback = '{field} failed validation'): string
    {
        if ($this->isValidatorInstance()) {
            return $this->validator->getMessage();
        }

        return $fallback;
    }


    /**
     * Check if the validator is resolved or not
     *
     * @return bool
     */
    public function isResolved(): bool
    {
        return is_string($this->validator) === false || is_callable($this->validator);
    }


    /**
     * Check if the validator is a resolved validator instance
     *
     * @return bool
     */
    public function isValidatorInstance(): bool
    {
        return $this->validator instanceof ValidatorInterface;
    }


    /**
     * Execute the validator
     *
     * @param array $arguments
     *
     * @return bool
     */
    public function execute(array $arguments): bool
    {
        return call_user_func_array($this->validator, $arguments);
    }
}
