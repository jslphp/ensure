<?php

namespace Jsl\Ensure\Components;

class Rules
{
    /**
     * @var array
     */
    protected array $rules;

    /**
     * @var bool
     */
    protected bool $required = false;

    /**
     * @var bool
     */
    protected bool $nullable = false;

    /**
     * @var string|null
     */
    protected string|null $fancyName = null;


    /**
     * @param array $rules
     */
    public function __construct(array $rules)
    {
        $this->setRules($rules);
    }


    /**
     * Set list of rules (merge with existing)
     *
     * @param array $rules
     *
     * @return self
     */
    public function setRules(array $rules): self
    {
        foreach ($rules as $rule => $args) {
            if (is_numeric($rule)) {
                $rule = (string)$args;
                $args = [];
            }

            $this->setRule($rule, (array)$args);
        }

        return $this;
    }


    /**
     * Set rule
     *
     * @param string $rule
     * @param array $args
     *
     * @return self
     */
    public function setRule(string $rule, array|string $args = []): self
    {
        if ($rule === 'required') {
            $this->required = true;
            return $this;
        }

        if ($rule === 'nullable') {
            $this->nullable = true;
            return $this;
        }

        if ($rule === 'as') {
            $this->fancyName = is_array($args)
                ? ($args[0] ?? null)
                : $args;

            return $this;
        }

        $this->rules[$rule] = (array)$args;

        return $this;
    }


    /**
     * Remove a rule
     *
     * @param string $rule
     *
     * @return self
     */
    public function remove(string $rule): self
    {
        if (key_exists($rule, $this->rules)) {
            unset($this->rules[$rule]);
        }

        return $this;
    }


    /**
     * Get all rules
     *
     * @return array
     */
    public function getRules(): array
    {
        return $this->rules;
    }


    /**
     * Get the fancy name, if we have it
     *
     * @return string|null
     */
    public function getFancyName(): ?string
    {
        return $this->fancyName;
    }


    /**
     * Check if we got the required rule
     *
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }


    /**
     * Check if we got the nullable rule
     *
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }
}
