<?php

namespace Jsl\Ensure\Validators\Fields;

use Jsl\Ensure\Abstracts\Validator;

class NotSameValidator extends Validator
{
    /**
     * @inheritDoc
     */
    protected string $message = '{field} cannot be same as {a:0}';


    /**
     * @param mixed $value
     * @param string $field
     *
     * @return bool
     */
    public function __invoke(mixed $value, string $field): bool
    {

        return $this->data->has($field) === false || $value !== $this->getField($field);
    }
}
