<?php

namespace Jsl\Ensure\Entities;

use InvalidArgumentException;
use Jsl\Ensure\Data\Data;
use Jsl\Ensure\Validators\Registry;

class FieldEntity
{
    /**
     * @var string
     */
    protected string $key;

    /**
     * @var string
     */
    protected string $name;

    /**
     * @var mixed
     */
    protected mixed $value;

    /**
     * @var array
     */
    protected array $rules = [];

    /**
     * @var bool
     */
    protected bool $isRequired = false;

    /**
     * @var bool
     */
    protected bool $isNullable = false;

    /**
     * @var bool
     */
    protected bool $missing = true;


    /**
     * @param string $key
     * @param array $rules
     */
    public function __construct(Data $data, string $key, array $rules = [])
    {
        $this->key = $key;
        $this->name = $key;
        $this->value = $data->get($key);
        $this->missing = $data->has($this->key) === false;

        foreach ($rules as $rule => $args) {
            $this->addRule($rule, $args);
        }
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
     * Get the field value
     *
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->value;
    }


    /**
     * Get the validation rules for the field
     *
     * @return array
     */
    public function getRules(): array
    {
        return $this->rules;
    }


    /**
     * Check if the value is null
     *
     * @return bool
     */
    public function isNull(): bool
    {
        return is_null($this->getValue());
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
        return $this->isRequired;
    }


    /**
     * Check if the field exists
     *
     * @return bool
     */
    public function isMissing(): bool
    {
        return $this->missing;
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
     * Add a rule for the field
     *
     * @param string|int $rule
     * @param mixed $args
     *
     * @return self
     */
    public function addRule(string|int $rule, mixed $args = []): self
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
}
