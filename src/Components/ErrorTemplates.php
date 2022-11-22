<?php

namespace Jsl\Ensure\Components;

class ErrorTemplates
{
    /**
     * @var array
     */
    protected array $templates = [
        'fields' => [],
        'rules' => [],
    ];


    /**
     * Set a custom error template for a field 
     * - Field errors will override any default rule errors for that field,
     * - but will not override custom rule errors added for that field
     *
     * @param string $field
     * @param string|null $template If null, the template won't be used
     *
     * @return self
     */
    public function setFieldTemplate(string $field, ?string $template = null): self
    {
        $this->templates['fields'][$field]['__main__'] = $template;

        return $this;
    }


    /**
     * Get a field template
     *
     * @param string $field
     * @param string|null $fallback
     *
     * @return string|null
     */
    public function getFieldTemplate(string $field, ?string $fallback = null): ?string
    {
        return $this->templates['fields'][$field]['__main__'] ?? $fallback;
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
        $this->templates['fields'][$field][$rule] = $template;

        return $this;
    }


    /**
     * Get a field rule template
     *
     * @param string $field
     * @param string $rule
     * @param string|null $fallback
     *
     * @return string|null
     */
    public function getFieldRuleTemplate(string $field, string $rule, ?string $fallback = null): ?string
    {
        return $this->templates['fields'][$field][$rule] ?? $fallback;
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
        $this->templates['rules'][$rule] = $template;

        return $this;
    }


    /**
     * Get a rule template
     *
     * @param string $rule
     * @param string|null $fallback
     *
     * @return string|null
     */
    public function getRuleTemplate(string $rule, ?string $fallback = null): ?string
    {
        return $this->templates['rules'][$rule] ?? $fallback;
    }


    /**
     * Render and return all active error templates
     *
     * @param Field[] $fields
     * @param bool $onlyFirst If set to true, only first error will be returned (default is false)
     *
     * @return array
     */
    public function renderErrors(array $fields, bool $onlyFirst = false): array
    {
        if (empty($fields)) {
            return [];
        }

        $errors = [];

        /**
         * @var Field $field
         */
        foreach ($fields as $key => $field) {
            $fieldTemplate = $this->getFieldTemplate($key);

            $hasCustomRuleTemplate = false;
            $rules = [];

            foreach ($field->getFailedRules() as $rule => $info) {
                if ($template = $this->getFieldRuleTemplate($key, $rule)) {
                    // We have a custom rule template so let's use that and set a flag
                    // so we know that we shouldn't use the custom field template
                    $hasCustomRuleTemplate = true;
                    $rules[] = $this->renderTemplate($field, $info['args'], $template);

                    continue;
                }

                // Get the custom rule template, if it exists. If not, use the validator rule template
                $template = $this->getRuleTemplate($rule, $info['template'] ?? '{field} is not valid');

                $rules[] = $this->renderTemplate($field, $info['args'], $template);
            }

            // If we found a custom field template but no custom rule templates, use that
            if ($fieldTemplate && $hasCustomRuleTemplate === false) {
                $errors[$key] = (array)$this->renderTemplate($field, [], $fieldTemplate);
                continue;
            }

            $errors[$key] = $rules;
        }

        if ($onlyFirst) {
            foreach ($errors as $field => $list) {
                $errors[$field] = $list[0];
            }
        }

        return $errors;
    }


    /**
     * Render a template (replace field name and argument placeholders)
     *
     * @param Field $field
     * @param array $args
     * @param string|null $template
     *
     * @return string
     */
    protected function renderTemplate(Field $field, array $args, string $template): string
    {
        $template = str_replace('{field}', $field->getFancyName(), $template);

        if ($args) {
            // Stringify any arrays  and remove any types that aren't printable.
            $args = array_map(function ($value) {
                if (is_array($value)) {
                    return implode(', ', $value);
                }

                if (is_bool($value)) {
                    return $value ? 'true' : 'false';
                }

                return is_string($value) || is_numeric($value) || (is_object($value) && method_exists($value, '__toString'))
                    ? (string)$value
                    : '';
            }, $args);

            // Create list with the same number of argument placeholders as we have arguments
            $replace = array_map(fn ($i) => '{a:' . $i . '}', range(0, count($args) - 1));
            $template = str_replace($replace, $args, $template);
        }

        return $template;
    }
}
