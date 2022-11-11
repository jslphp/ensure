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
     * @var ErrorsMiddlewareInterface
     */
    protected ErrorsMiddlewareInterface $middleware;


    /**
     * @param ErrorsMiddlewareInterface $middleware
     * @param FieldResult[] $fields
     */
    public function __construct(ErrorsMiddlewareInterface $middleware, array $fields = [])
    {
        $this->middleware = $middleware;
        $this->fields = $fields;

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
     * @param array $customRuleErrors
     *
     * @return array
     */
    public function errors(?ErrorsMiddlewareInterface $middleware = null, array $customRuleErrors = []): array
    {
        $middleware = $middleware ?: $this->middleware;
        $key = md5(get_class($middleware));

        if (empty($customRuleErrors) && key_exists($key, $this->errors)) {
            return $this->errors[$key];
        }

        $this->errors[$key] = ['_' => []];

        foreach ($this->fields as $field => $result) {
            if ($result->success() === false) {
                $this->success = false;
                $messages = $result->errors($middleware, $customRuleErrors);
                $this->errors[$key][$field] = (array)$messages;
                $this->errors[$key]['_'] = array_merge($this->errors[$key]['_'], (array)$messages);
            }
        }

        return $this->errors[$key];
    }
}
