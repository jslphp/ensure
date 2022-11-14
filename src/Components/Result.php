<?php

namespace Jsl\Ensure\Components;

use Jsl\Ensure\Contracts\ErrorsMiddlewareInterface;

class Result
{
    /**
     * @var bool
     */
    protected bool $success = true;

    /**
     * @var FieldResult[]
     */
    protected array $fields = [];

    /**
     * @var array
     */
    protected array $errors = [];

    /**
     * @var array
     */
    protected array $customErrors = [];

    /**
     * @var ErrorsMiddlewareInterface
     */
    protected ErrorsMiddlewareInterface $middleware;


    /**
     * @param ErrorsMiddlewareInterface $middleware
     * @param FieldResult[] $fields
     * @param array $customErrors
     */
    public function __construct(ErrorsMiddlewareInterface $middleware, array $fields = [], array $customErrors = [])
    {
        $this->middleware = $middleware;
        $this->fields = $fields;
        $this->customErrors = $customErrors;

        $this->errors();
    }


    /**
     * Check if all validation passed
     *
     * @return bool
     */
    public function success(): bool
    {
        return $this->success;
    }


    /**
     * Get list of all failed rules and arguments
     *
     * @return array
     */
    public function failedRules(): array
    {
        $fields = [];

        foreach ($this->fields as $field => $result) {
            $fields[$field] = $result->getFailedRules();
        }

        return $fields;
    }


    /**
     * Get list of all failed rules
     *
     * @return array
     */
    public function failedRuleNames(): array
    {
        $rules = [];

        foreach ($this->fields as $field => $result) {
            $rules[$field] = $result->getFailedRuleNames();
        }

        return $rules;
    }


    /**
     * Get list of all failed fields
     *
     * @return array
     */
    public function failedFields(): array
    {
        return array_keys($this->failedFields);
    }


    /**
     * Get all validation errros, if any
     *
     * @param ErrorsMiddlewareInterface|null $middleware
     * @param array|null $customErrors
     *
     * @return array
     */
    public function errors(?ErrorsMiddlewareInterface $middleware = null, array|null $customErrors = null): array
    {
        $middleware = $middleware ?: $this->middleware;
        $key = md5(get_class($middleware));

        $customErrors = $customErrors ?: $this->customErrors;

        if (empty($customErrors) && key_exists($key, $this->errors)) {
            return $this->errors[$key];
        }

        foreach ($this->fields as $field => $result) {
            if ($result->success() === false) {
                $this->success = false;
                $messages = $result->errors($middleware, $customErrors);
                $this->errors[$key][$field] = (array)$messages;
            }
        }

        return $this->errors[$key] ?? [];
    }
}
