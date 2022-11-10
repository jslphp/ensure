<?php

namespace Jsl\Ensure\Entities;

class ResultEntity
{
    /**
     * @var bool
     */
    public readonly bool $success;

    /**
     * @var array
     */
    public readonly array $errors;


    /**
     * @param bool $success
     * @param array $errors
     */
    public function __construct(bool $success, array $errors)
    {
        $this->errors = $errors;
        $this->success = $success;
    }
}
