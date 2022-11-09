<?php

namespace Jsl\Ensure\Abstracts;

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
     * Get the error message
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
