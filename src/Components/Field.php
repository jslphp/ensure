<?php

namespace Jsl\Ensure\Components;

class Field
{
    /**
     * @var string
     */
    protected string $key;

    /**
     * @var Rules
     */
    public readonly Rules $rules;

    /**
     * @var Values
     */
    protected Values $values;

    /**
     * @var array
     */
    protected array $failed = [];


    /**
     * @param string $key
     * @param array $rules
     * @param Values $values
     */
    public function __construct(string $key, array $rules, Values $values)
    {
        $this->key = $key;
        $this->rules = new Rules($rules);
        $this->values = $values;
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
     * Get the fancy name, or return the key if no fancy name is set
     *
     * @return string
     */
    public function getFancyName(): string
    {
        return $this->rules->getFancyName() ?? $this->getKey();
    }


    /**
     * Set a rule
     *
     * @param string $rule
     * @param array $args
     *
     * @return self
     */
    public function setRule(string $rule, array $args = []): self
    {
        $this->rules->setRule($rule, $args);

        return $this;
    }


    /**
     * Set multiple rules
     *
     * @param array $rules
     *
     * @return self
     */
    public function setRules(array $rules): self
    {
        $this->rules->setRules($rules);

        return $this;
    }


    /**
     * Remove a rule from the field
     *
     * @param string $rule
     *
     * @return self
     */
    public function removeRule(string $rule): self
    {
        $this->rules->remove($rule);

        return $this;
    }


    /**
     * Check if the field is required
     *
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->rules->isRequired();
    }


    /**
     * Check if the field is nullable
     *
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->rules->isNullable();
    }


    /**
     * Check if the value is null
     *
     * @return bool
     */
    public function isNull(): bool
    {
        return $this->values->isNull($this->getKey());
    }


    /**
     * Check if the value exist
     *
     * @return bool
     */
    public function hasValue(): bool
    {
        return $this->values->has($this->getKey());
    }


    /**
     * Get the field value
     *
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->values->get($this->getKey());
    }


    /**
     * Check if there are any failed rules
     *
     * @return bool
     */
    public function hasFailedRules(): bool
    {
        return empty($this->failed) === false;
    }


    /**
     * Clear all previous added failed rules
     *
     * @return self
     */
    public function clearFailedRules(): self
    {
        $this->failed = [];

        return $this;
    }


    /**
     * Add a failed rule to the field
     *
     * @param string $rule
     * @param array $args
     * @param string|null $template
     *
     * @return self
     */
    public function addFailedRule(string $rule, array $args = [], ?string $template = null): self
    {
        $this->failed[$rule] = [
            'rule' => $rule,
            'args' => $args,
            'template' => $template,
        ];

        return $this;
    }


    /**
     * Get all failed rules
     *
     * @return array
     */
    public function getFailedRules(): array
    {
        return $this->failed;
    }
}
