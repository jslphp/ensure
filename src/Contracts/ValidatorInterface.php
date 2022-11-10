<?php

namespace Jsl\Ensure\Contracts;

use Jsl\Ensure\Data\Data;

interface ValidatorInterface
{
    /**
     * Get the validation error
     * 
     * @return string
     */
    public function getMessage(): string;


    /**
     * Set the data object
     *
     * @param Data $data
     *
     * @return self
     */
    public function setData(Data $data): self;
}
