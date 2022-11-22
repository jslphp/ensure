<?php

namespace Jsl\Ensure\Components;

use Jsl\Ensure\Traits\SetTemplatesTrait;

class Result
{
    use SetTemplatesTrait;

    /**
     * @var Field[]
     */
    protected array $fields = [];

    /**
     * @var ErrorTemplates
     */
    protected ErrorTemplates $templates;


    /**
     * @param array $failedFields
     */
    public function __construct(array $failedFields, ErrorTemplates $templates)
    {
        $this->fields = $failedFields;
        $this->templates = $templates;
    }


    /**
     * Check if the validation was valid
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return empty($this->fields) === true;
    }


    /**
     * Get all errors
     * 
     * @param bool $onlyFirst If true, only the first error for each field will be returned (Defaults to false)
     *
     * @return array
     */
    public function getErrors(bool $onlyFirst = false): array
    {
        return $this->templates->renderErrors($this->fields, $onlyFirst);
    }
}
