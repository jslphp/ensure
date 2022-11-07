<?php

namespace Jsl\Ensure\Components;

/**
 * @method self email()
 * @method self url()
 * @method self mac()
 * @method self ip()
 * @method self ipv4()
 * @method self ipv6()
 * @method self alpha()
 * @method self alphanum()
 * @method self hex()
 * @method self octal()
 * @method self string()
 * @method self integer()
 * @method self numeric()
 * @method self float()
 * @method self array()
 * @method self boolean()
 * @method self startsWith(string $text)
 * @method self notStartsWith(string $text)
 * @method self endsWith(string $text)
 * @method self notEndsWith(string $text)
 * @method self contains(string $text)
 * @method self notContains(string $text)
 * @method self regex(string $expression)
 * @method self in(array $list)
 * @method self notIn(array $list)
 * @method self size(int|float $size)
 * @method self minSize(int|float $threshold)
 * @method self maxSize(int|float $threshold)
 * @method self same(string $field)
 * @method self notSame(string $field)
 */
class Field
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
     * @var Result
     */
    protected Result $result;

    /**
     * @var string
     */
    protected string $field;

    /**
     * @var mixed
     */
    protected mixed $value;

    /**
     * @var bool
     */
    protected bool $isNull;

    /**
     * @var bool
     */
    protected bool $missing;

    /**
     * @var bool
     */
    protected bool $required = false;

    /**
     * @var bool
     */
    protected bool $nullable = false;


    /**
     * @param Values $values
     * @param Registry $registry
     * @param string $field
     */
    public function __construct(Values $values, Registry $registry, string $field)
    {
        $this->values = $values;
        $this->registry = $registry;
        $this->field = $field;

        $this->missing = $this->values->has($field) === false;
        $this->value = $this->missing ? null : $this->values->get($field);
        $this->isNull = $this->missing === false && is_null($this->value);

        $this->result = new Result;
    }


    /**
     * @param string $validator
     * @param array $arguments
     *
     * @return self
     */
    public function __call($validator, $arguments): self
    {
        if ($this->missing || $this->isNull) {
            return $this;
        }

        $success = $this->registry->execute(
            $validator,
            array_merge([$this->value], $arguments),
            $this->values
        );

        if ($success !== true) {
            $this->result->addError($this->field, $validator, $arguments);
        }

        return $this;
    }


    /**
     * Set the field as required
     *
     * @return self
     */
    public function required(): self
    {
        if ($this->required === true) {
            return $this;
        }

        $this->required = true;

        if ($this->missing) {
            $this->result->addError($this->field, 'required');
        }

        return $this;
    }


    /**
     * Set the field as nullable
     *
     * @return self
     */
    public function nullable(): self
    {
        $this->nullable = true;

        return $this;
    }


    /**
     * Check if all validations was successful
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->getResult()->isValid();
    }


    /**
     * Check if we had any validation errors
     *
     * @return bool
     */
    public function isInvalid(): bool
    {
        return $this->getResult()->isInvalid();
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
        return $this->getResult()->getErrors($onlyFieldNames);
    }

    /**
     * Get the result
     *
     * @return Result
     */
    public function getResult(): Result
    {
        if ($this->nullable === false && $this->isNull) {
            $this->result->addError($this->field, 'null');
        }

        return $this->result;
    }
}
