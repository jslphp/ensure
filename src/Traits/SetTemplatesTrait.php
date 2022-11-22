<?php

namespace Jsl\Ensure\Traits;

trait SetTemplatesTrait
{
    /**
     * Set a custom error template for a field 
     * - Field errors will override any default rule errors for that field,
     * - but will not override custom rule errors added for that field
     *
     * @param string $field
     * @param string|null $template
     *
     * @return self
     */
    public function setFieldTemplate(string $field, ?string $template = null): self
    {
        $this->templates->setFieldTemplate($field, $template);

        return $this;
    }


    /**
     * Set a custom error template for a field rule
     * - Field rule templates has the highest priority.
     * - They will override any default rule templates and any field templates
     *
     * @param string $field
     * @param string $rule
     * @param string|null $template
     *
     * @return self
     */
    public function setFieldRuleTemplate(string $field, string $rule, ?string $template = null): self
    {
        $this->templates->setFieldRuleTemplate($field, $rule, $template);

        return $this;
    }


    /**
     * Set a custom error template for a rule 
     * - Override a default rule error template
     *
     * @param string $rule
     * @param string|null $template
     *
     * @return self
     */
    public function setRuleTemplate(string $rule, ?string $template = null): self
    {
        $this->templates->setRuleTemplate($rule, $template);

        return $this;
    }
}
