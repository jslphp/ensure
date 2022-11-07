<?php

namespace Jsl\Ensure\Validators\Traits;

use Jsl\Ensure\Components\Values;

trait RequireValuesTrait
{
    /**
     * @var Values
     */
    protected Values $values;


    /**
     * Add values
     *
     * @param Values $values
     *
     * @return self
     */
    public function setValues(Values $values): self
    {
        $this->values = $values;

        return $this;
    }
}
