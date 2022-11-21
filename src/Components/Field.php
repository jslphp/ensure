<?php

namespace Jsl\Ensure\Components;

use InvalidArgumentException;

class Field
{
    /**
     * @var Container
     */
    protected Container $container;

    /**
     * @var Data
     */
    protected Data $data;

    /**
     * @var Validators
     */
    protected Validators $validators;

    /**
     * @var string
     */
    protected string $key;

    /**
     * @var string
     */
    protected string $name;

    /**
     * @var array
     */
    protected array $rules = [];

    /**
     * @var array
     */
    protected array $customRuleErrors = [];

    /**
     * @var bool
     */
    protected bool $isRequired = false;

    /**
     * @var bool
     */
    protected bool $isNullable = false;


    /**
     * @param string $key
     * @param Container $container
     */
    public function __construct(string $key, ?Container $container = null)
    {
        $this->container  = $container ?: new Container;

        $this->data       = $this->container->data();
        $this->validators = $this->container->validators();

        $this->key  = $key;
        $this->name = $key;
    }


    /**
     * Get the field key
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }


    /**
     * Set the field name
     *
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    /**
     * Get the field name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * Set the data to be validated
     *
     * @param array $data
     *
     * @return self
     */
    public function setData(array $data): self
    {
        $this->data->set($this->key, $data);

        return $this;
    }


    /**
     * Set field rules
     *
     * @param array $rules
     *
     * @return self
     */
    public function setRules(array $rules): self
    {
        foreach ($rules as $rule => $args) {
            $this->setRule($rule, $args);
        }

        return $this;
    }


    /**
     * Add a rule for the field
     *
     * @param string|int $rule
     * @param mixed $args
     *
     * @return self
     */
    public function setRule(string|int $rule, mixed $args = []): self
    {
        if (is_numeric($rule) && is_string($args)) {
            $rule = $args;
            $args = [];
        }

        if ($rule === 'required') {
            $this->isRequired = true;
            return $this;
        }

        if ($rule === 'nullable') {
            $this->isNullable = true;
            return $this;
        }

        if ($rule === 'as') {
            if (is_string($args)) {
                $this->name = $args;
            } else if (is_array($args) && count($args) > 0) {
                $this->name = $args[0];
            } else {
                throw new InvalidArgumentException("Invalid arguments for the rule 'as'");
            }

            return $this;
        }

        $this->rules[$rule] = (array)$args;

        return $this;
    }


    /**
     * Set a custom error message for a rule
     *
     * @param string $rule
     * @param string|null $message
     *
     * @return self
     */
    public function setError(string $rule, ?string $message = null): self
    {
        $this->customRuleErrors[$rule] = $message;

        return $this;
    }


    /**
     * Replace a single or all arguments
     *
     * @param string $rule
     * @param mixed $args
     * @param string|int|null $index
     *
     * @return self
     */
    public function replaceArguments(string $rule, mixed $args, string|int|null $index = null): self
    {
        if (key_exists($rule, $this->rules) === false) {
            return $this;
        }

        if ($index === null) {
            $this->rules[$rule] = (array)$args;
        } else {
            $this->rules[$rule][$index] = $args;
        }

        return $this;
    }


    /**
     * Check if the field is required
     *
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->isRequired;
    }


    /**
     * Check if the field is nullable
     *
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->isNullable;
    }


    /**
     * Check if the field exists
     *
     * @return bool
     */
    public function isMissing(): bool
    {
        return $this->data->has($this->key) === false;
    }


    /**
     * Check if the value is null
     *
     * @return bool
     */
    public function isNull(): bool
    {
        return is_null($this->data->get($this->key, 'not null'));
    }


    /**
     * Check if the field should be skipped
     *
     * @return bool
     */
    public function shouldBeSkipped(): bool
    {
        return $this->isMissing() || $this->isNull();
    }


    /**
     * Get the fields custom rule errors
     *
     * @return array
     */
    public function getCustomRuleErrors(): array
    {
        return $this->customRuleErrors;
    }


    /**
     * Validate the field against its rules
     *
     * @return FieldResult
     */
    public function validate(): FieldResult
    {
        $failed = [];
        $success = true;
        $middleware = $this->container->errorsMiddleware();

        // Is required
        if ($this->isRequired() && $this->isMissing()) {
            $failed['required'] = [
                'args'    => [],
                'message' => '{field} is required',
            ];

            return new FieldResult($middleware, $this, false, $failed);
        }

        // Nullable
        if ($success & $this->isNull() && $this->isNullable() === false) {
            $failed['nullable'] = [
                'args'    => [],
                'message' => '{field} cannot be null',
            ];

            return new FieldResult($middleware, $this, false, $failed);
        }

        // Got null
        if ($this->isNull()) {
            return new FieldResult($middleware, $this, true, []);
        }

        $success = true;

        foreach ($this->rules as $rule => $args) {
            [$ruleSuccess, $message] = $this->validators->execute($this->data, $this->key, $rule, $args);

            if ($ruleSuccess === false) {
                $failed[$rule] = [
                    'args'    => $args,
                    'message' => $message,
                ];

                $success = false;
            }
        }

        return new FieldResult($middleware, $this, $success, $failed);
    }
}
