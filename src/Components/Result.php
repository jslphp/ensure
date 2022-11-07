<?php

namespace Jsl\Ensure\Components;

class Result
{
    /**
     * @var array
     */
    protected array $errors = [];

    /**
     * @var bool
     */
    protected bool $success = true;


    /**
     * Check if validation was successful
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->success;
    }


    /**
     * Check if validation failed
     *
     * @return bool
     */
    public function isInvalid(): bool
    {
        return $this->isValid() === false;
    }


    /**
     * Add an error
     *
     * @param string $field
     * @param string $rule
     * @param array $args
     *
     * @return self
     */
    public function addError(string $field, string $rule, array $args = []): self
    {
        $this->success = false;

        $this->errors[$field] = (object)[
            'rule' => $rule,
            'args' => $args,
        ];

        return $this;
    }


    /**
     * Get all errros
     *
     * @param bool $onlyFieldNames
     *
     * @return array
     */
    public function getErrors(bool $onlyFieldNames = false): array
    {
        return $onlyFieldNames ? array_keys($this->errors) : $this->errors;
    }
}
