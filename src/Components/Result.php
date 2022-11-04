<?php

namespace Jsl\Ensure\Components;

class Result
{
    /**
     * @var bool
     */
    protected bool $isValid = true;

    /**
     * @var array
     */
    protected array $errors = [];


    /**
     * Check if the 
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }


    /**
     * Add an error to the result
     *
     * @param string $field
     * @param string|null $message
     *
     * @return self
     */
    public function setFieldAsFailed(string $field, ?string $message = null): self
    {
        $this->isValid = false;

        if ($message) {
            $this->errors[$field] = $message;
        }

        return $this;
    }


    /**
     * Get all errors, if any
     *
     * @return array
     */
    public function errors(): array
    {
        return $this->errors;
    }
}
