<?php

namespace Jsl\Ensure\Validators\Fields;

use Jsl\Ensure\Abstracts\Validator;

class SameValidator extends Validator
{
    /**
     * @inheritDoc
     */
    protected string $message = '{field} did not match {a:0}';


    /**
     * @param mixed $value
     * @param string $field
     *
     * @return bool
     */
    public function __invoke(mixed $value, string $field): bool
    {

        return $this->data->has($field) && $value === $this->getField($field);
    }
}
