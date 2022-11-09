<?php

namespace Jsl\Ensure\Contracts;

interface ValidatorInterface
{
    /**
     * Get the validation error
     * 
     * @return string
     */
    public function getMessage(): string;
}
