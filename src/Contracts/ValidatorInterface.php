<?php

namespace Jsl\Ensure\Contracts;

use Jsl\Ensure\Components\Values;

interface ValidatorInterface
{
    /**
     * Get the error template
     * 
     * @return string
     */
    public function getTemplate(): string;


    /**
     * Set the values object
     *
     * @param Values $data
     *
     * @return self
     */
    public function setValues(Values $values): self;
}
