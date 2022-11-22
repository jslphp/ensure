<?php

namespace Jsl\Ensure\Validators\Fields;

use Jsl\Ensure\Abstracts\Validator;

class DifferentValidator extends Validator
{
    /**
     * @inheritDoc
     */
    protected string $template = '{field} cannot be same as {a:0}';


    /**
     * @param mixed $value
     * @param string $field
     *
     * @return bool
     */
    public function __invoke(mixed $value, string $field): bool
    {
        return $this->values->has($field) === false || $value !== $this->getValue($field);
    }
}
