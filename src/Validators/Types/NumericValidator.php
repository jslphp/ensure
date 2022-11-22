<?php

namespace Jsl\Ensure\Validators\Types;

use Jsl\Ensure\Abstracts\Validator;

class NumericValidator extends Validator
{
    /**
     * @inheritDoc
     */
    protected string $template = '{field} must be numeric';


    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function __invoke(mixed $value): bool
    {
        return is_numeric($value);
    }
}
