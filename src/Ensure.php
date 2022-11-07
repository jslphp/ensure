<?php

namespace Jsl\Ensure;

use Jsl\Ensure\Components\Field;
use Jsl\Ensure\Components\Messages;
use Jsl\Ensure\Components\Registry;
use Jsl\Ensure\Components\Result;
use Jsl\Ensure\Components\Rulesets;
use Jsl\Ensure\Components\Values;

class Ensure
{
    /**
     * @var Values
     */
    protected Values $values;

    /**
     * @var Registry
     */
    protected Registry $registry;

    /**
     * @var Rulesets
     */
    protected Rulesets $rulesets;

    /**
     * @var Result
     */
    protected Result $result;

    /**
     * @var Messages
     */
    protected Messages $messages;

    /**
     * @var array
     */
    protected array $fields = [];

    /**
     * @var bool
     */
    protected bool $validated = false;

    /**
     * @var array
     */
    protected array $rules = [];

    /**
     * @var array
     */
    protected array $fieldNames = [];


    /**
     * @param array $values
     * @param Registry $registry
     * @param Rulesets $rulesets
     * @param array $ruleset
     */
    public function __construct(array $values, Registry $registry, Rulesets $rulesets, string|array $rules = [])
    {
        $this->values = new Values($values);
        $this->registry = $registry;
        $this->rulesets = $rulesets;
        $this->addRules($rules);
        $this->messages = new Messages;

        // Create new field objects
        foreach (array_keys($values) as $field) {
            $this->fields[$field] = $this->field($field);
        }
    }


    /**
     * Get field validator
     *
     * @param string $field
     *
     * @return Field
     */
    public function field(string $field): Field
    {
        return new Field($this->values, $this->registry, $field);
    }


    /**
     * Set nice field names
     *
     * @param array $names
     *
     * @return self
     */
    public function setFieldNames(array $names): self
    {
        $this->fieldNames = $names;

        return $this;
    }


    /**
     * Check if all validations was successful
     *
     * @return bool
     */
    public function isValid(): bool
    {
        $this->run();

        foreach ($this->fields as $field) {
            if ($field->isValid() === false) {
                return false;
            }
        }

        return true;
    }


    /**
     * Check if we had any validation errors
     *
     * @return bool
     */
    public function isInvalid(): bool
    {
        return $this->isValid() === false;
    }


    /**
     * Get all errors, if any
     *
     * @param bool $onlyFieldNames
     * 
     * @return array
     */
    public function getErrors(bool $onlyFieldNames = false): array
    {
        if ($this->isValid()) {
            return [];
        }

        $errors = [];

        foreach ($this->fields as $name => $field) {
            if ($list = $field->getErrors($onlyFieldNames)) {
                $errors[$name] = $list;
            }
        }

        return $errors;
    }


    /**
     * Get errors with custom error messages
     *
     * @param array $messages
     * @param array $names
     *
     * @return array
     */
    public function getErrorsWithMessages(array $messages): array
    {
        return $this->messages->create($this->getErrors(), $messages, $this->fieldNames);
    }


    /**
     * Add rules
     *
     * @param string|array $rules
     *
     * @return self
     */
    public function addRules(string|array $rules): self
    {
        $this->rules = is_string($rules)
            ? $this->rulesets->get($rules)
            : $rules;

        return $this;
    }


    /**
     * Validate fields using a ruleset
     *
     * @param array $ruleset
     *
     * @return self
     */
    protected function run(): self
    {
        foreach ($this->rules as $fieldName => $rules) {
            if (isset($this->fields[$fieldName]) === false) {
                $this->fields[$fieldName] = $this->field($fieldName);
            }

            $field = $this->fields[$fieldName];

            foreach ($rules as $validator => $args) {
                if (is_numeric($validator)) {
                    $validator = $args;
                    $args = [];
                }

                call_user_func_array([$field, $validator], (array)$args);
            }
        }

        return $this;
    }
}
