<?php

namespace Jsl\Ensure;

use Jsl\Ensure\Components\Container;
use Jsl\Ensure\Components\Data;
use Jsl\Ensure\Components\Field;
use Jsl\Ensure\Components\Result;
use Jsl\Ensure\Exceptions\UnknownFieldException;

class Ensure
{
    /**
     * @var Container
     */
    protected Container $container;

    /**
     * @var Field[]
     */
    protected array $fields = [];

    /**
     * @var Result|null
     */
    protected Result|null $result = null;


    /**
     * @param array $data
     * @param array|string $rules
     * @param Container|null $container
     */
    public function __construct(array $data, array|string $rules = [], ?Container $container = null)
    {
        $this->container = $container ?: new Container;
        $this->container->setData(new Data($data));

        $this->setRules($rules);
    }


    /**
     * Set validation rules
     *
     * @param array|string $rules
     *
     * @return self
     */
    public function setRules(array|string $rules): self
    {
        if (is_string($rules)) {
            $rules = $this->container->rulesets()->get($rules);
        }

        foreach ($rules as $field => $fieldRules) {
            $this->fields[$field] = (new Field($field, $this->container))
                ->setRules($fieldRules);
        }

        return $this;
    }


    /**
     * Get a field
     *
     * @param string $field
     *
     * @return Field
     */
    public function field(string $field): Field
    {
        if (key_exists($field, $this->fields) === false) {
            throw new UnknownFieldException("Unknown field: {$field}");
        }

        return $this->field[$field];
    }


    /**
     * Set a rule for a specific field
     *
     * @param string $field
     * @param string $rule
     * @param mixed $args
     *
     * @return self
     */
    public function setRule(string $field, string $rule, mixed $args = []): self
    {
        if (key_exists($field, $this->fields) === false) {
            $this->fields[$field] = new Field($field, $this->container);
        }

        $this->field($field)
            ->setRule($rule, $args);

        return $this;
    }


    /**
     * Replace a single or all arguments
     *
     * @param string $field
     * @param string $rule
     * @param mixed $args
     * @param string|int|null $index
     *
     * @return self
     */
    public function replaceArguments(string $field, string $rule, mixed $args, string|int|null $index = null): self
    {
        if (key_exists($field, $this->fields) === false) {
            return $this;
        }

        $this->fields[$field]->replaceArguments($rule, $args, $index);

        return $this;
    }


    /**
     * Set nice names for fields (used for error messages)
     *
     * @param array $names [fieldname => nicename, ...]
     *
     * @return self 
     */
    public function setFieldNames(array $names): self
    {
        foreach ($names as $field => $name) {
            if (key_exists($field, $this->fields)) {
                $this->fields[$field]->setName($name);
            }
        }

        return $this;
    }


    /**
     * Validate all fields
     *
     * @return Result
     */
    public function validate(): Result
    {
        if ($this->result) {
            return $this->result;
        }

        $fields = [];

        foreach ($this->fields as $key => $field) {
            $fields[$key] = $field->validate();
        }

        return $this->result = new Result($this->container->errorsMiddleware(), $fields);
    }
}
