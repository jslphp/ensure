<?php

namespace Jsl\Ensure\Components;

use Jsl\Ensure\Contracts\ErrorsMiddlewareInterface;

class FieldResult
{
    /**
     * @var ErrorsMiddlewareInterface
     */
    protected ErrorsMiddlewareInterface $middleware;

    /**
     * @var Field
     */
    protected Field $field;

    /**
     * @var bool
     */
    protected bool $success;

    /**
     * @var array
     */
    protected array $failedRules = [];


    /**
     * @param ErrorsMiddlewareInterface $middleware
     * @param Field $field
     * @param bool $success
     * @param array $failedRules
     */
    public function __construct(ErrorsMiddlewareInterface $middleware, Field $field, bool $success, array $failedRules = [])
    {
        $this->middleware = $middleware;
        $this->field = $field;
        $this->success = $success;
        $this->failedRules = $failedRules;
    }


    /**
     * Check if the validation was successful
     *
     * @return bool
     */
    public function success(): bool
    {
        return $this->success;
    }


    /**
     * Get the names of all failed rules
     * 
     * @return array 
     */
    public function getFailedRuleNames(): array
    {
        return array_keys($this->failedRules);
    }


    /**
     * Get all failed rules
     *
     * @return array
     */
    public function getFailedRules(): array
    {
        return $this->failedRules;
    }


    /**
     * Get all error messages
     * 
     * @param ErrorsMiddlewareInterface|null $middleware
     * @param array $customRuleErrors
     * 
     * @return string|array
     */
    public function errors(?ErrorsMiddlewareInterface $middleware = null, array $customRuleErrors = []): string|array
    {
        return ($middleware ?? $this->middleware)->renderErrors(
            $this->field,
            $this->failedRules,
            $customRuleErrors
        );
    }
}
