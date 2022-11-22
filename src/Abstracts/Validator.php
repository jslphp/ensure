<?php

namespace Jsl\Ensure\Abstracts;

use Jsl\Ensure\Components\Values;
use Jsl\Ensure\Contracts\ValidatorInterface;

abstract class Validator implements ValidatorInterface
{
    /**
     * @var string
     */
    protected string $template = '{field} failed validation';


    /**
     * @var Values|null
     */
    protected Values|null $values = null;


    /**
     * @inheritDoc
     */
    public function getTemplate(): string
    {
        return $this->template;
    }


    /**
     * @inheritDoc
     */
    public function setValues(Values $values): self
    {
        $this->values = $values;

        return $this;
    }



    /**
     * Get a field value
     *
     * @param string $field
     *
     * @return mixed
     */
    protected function getValue(string $field): mixed
    {
        return $this->values->get($field);
    }
}
