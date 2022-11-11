<?php

namespace Jsl\Ensure\Abstracts;

use Jsl\Ensure\Components\Data;
use Jsl\Ensure\Contracts\ValidatorInterface;

abstract class Validator implements ValidatorInterface
{
    /**
     * Error message
     *
     * @var string
     */
    protected string $message = '{field} failed validation';


    /**
     * Get a value from the data object
     *
     * @var Data|null
     */
    protected Data|null $data = null;


    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return $this->message;
    }


    /**
     * @inheritDoc
     */
    public function setData(Data $data): self
    {
        $this->data = $data;

        return $this;
    }


    /**
     * Get a field value from the data object
     *
     * @param string $field
     *
     * @return mixed
     */
    protected function getField(string $field): mixed
    {
        return $this->data->get($field);
    }
}
