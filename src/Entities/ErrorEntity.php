<?php

namespace Jsl\Ensure\Entities;

use JsonSerializable;

class ErrorEntity implements JsonSerializable
{
    /**
     * @var FieldEntity
     */
    protected FieldEntity $field;

    /**
     * @var string
     */
    protected string $rule;

    /**
     * @var array
     */
    protected array $args;

    /**
     * @var string|null
     */
    protected ?string $message = null;


    /**
     * Add an error
     *
     * @param FieldEntity $field
     * @param string $rule
     * @param array $args
     * @param string|null $message
     */
    public function __construct(FieldEntity $field, string $rule, array $args = [], ?string $message = null)
    {
        $this->field = $field;
        $this->rule = $rule;
        $this->args = $args;
        $this->message = $message;
    }


    /**
     * Get the field
     *
     * @return FieldEntity
     */
    public function getField(): FieldEntity
    {
        return $this->field;
    }


    /**
     * Get the field key
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->field->getKey();
    }


    /**
     * Get the rule
     *
     * @return string
     */
    public function getRule(): string
    {
        return $this->rule;
    }


    /**
     * Get the arguments
     *
     * @return array
     */
    public function getArguments(): array
    {
        return $this->args;
    }


    /**
     * Get the message
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }


    /**
     * Data to be encoded to json
     *
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return $this->getMessage();
    }
}
