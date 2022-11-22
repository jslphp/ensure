<?php

namespace Jsl\Ensure;

use Closure;
use Jsl\Ensure\Components\ErrorTemplates;
use Jsl\Ensure\Components\Field;
use Jsl\Ensure\Components\Result;
use Jsl\Ensure\Components\Validators;
use Jsl\Ensure\Components\Values;
use Jsl\Ensure\Contracts\ValidatorInterface;
use Jsl\Ensure\Exceptions\UnknownFieldException;
use Jsl\Ensure\Traits\SetTemplatesTrait;

class Ensure
{
    use SetTemplatesTrait;

    /**
     * @var Field[]
     */
    protected array $fields = [];

    /**
     * @var Values
     */
    protected Values $values;

    /**
     * @var Validators
     */
    protected Validators $validators;

    /**
     * @var ErrorTemplates
     */
    protected ErrorTemplates $templates;


    /**
     * @param array $rules
     * @param array $values
     * @param Validators|null $validators
     * @param ErrorTemplates $errors
     */
    public function __construct(array $rules, array $values, ?Validators $validators = null, ?ErrorTemplates $templates = null)
    {
        $this->values = new Values($values);
        $this->validators = $validators ?? new Validators;
        $this->templates = $templates ?? new ErrorTemplates;

        foreach ($rules as $key => $fieldRules) {
            if (empty($fieldRules)) {
                // If there aren't any rules defined, skip it
                continue;
            }

            $this->fields[$key] = new Field($key, $fieldRules, $this->values);
        }
    }


    /**
     * Set the field separator
     *
     * @param string $separator
     *
     * @return self
     */
    public function setFieldSeparator(string $separator): self
    {
        $this->values->setFieldSeparator($separator);

        return $this;
    }


    /**
     * Set "fancy" names for fields for the error messages
     *
     * @param array $names
     *
     * @return self
     */
    public function setFancyNames(array $names): self
    {
        foreach ($names as $field => $name) {
            $this->setFieldRule($field, 'as', $name);
        }

        return $this;
    }


    /**
     * Set the validator class resolver
     *
     * @param Closure $resolver
     *
     * @return self
     */
    public function setClassResolver(Closure $resolver): self
    {
        $this->validators->setClassResolver($resolver);

        return $this;
    }


    /**
     * Set a field rule
     *
     * @param string $field
     * @param string $rule
     * @param mixed $args
     *
     * @return self
     */
    public function setFieldRule(string $field, string $rule, ...$args): self
    {
        if (key_exists($field, $this->fields) === false) {
            $this->fields[$field] = new Field($field, [], $this->values);
        }

        $this->fields[$field]->setRule($rule, $args);

        return $this;
    }


    /**
     * Set multiple field rules
     *
     * @param string $field
     * @param array $rules
     *
     * @return self
     */
    public function setFieldRules(string $field, array $rules): self
    {
        if (key_exists($field, $this->fields) === false) {
            $this->fields[$field] = new Field($field, [], $this->values);
        }

        $this->fields[$field]->setRules($rules);

        return $this;
    }


    /**
     * Remove a field rule
     *
     * @param string $field
     * @param string $rule
     *
     * @return self
     */
    public function removeFieldRule(string $field, string $rule): self
    {
        if (key_exists($field, $this->fields) === false) {
            throw new UnknownFieldException("Unknown field: {$field}");
        }

        $this->fields[$field]->removeRule($rule);

        return $this;
    }

    /**
     * Add validator
     *
     * @param string $name
     * @param mixed $callback
     * @param string|null $template
     *
     * @return self
     */
    public function addValidator(string $name, mixed $callback, ?string $template = null): self
    {
        $this->validators->add($name, $callback);

        if ($template) {
            $this->templates->setRuleTemplate($name, $template);
        }

        return $this;
    }


    /**
     * Set a specific field value
     *
     * @param string $field
     * @param mixed $value
     *
     * @return self
     */
    public function setFieldValue(string $field, mixed $value): self
    {
        $this->values->set($field, $value);

        return $this;
    }


    /**
     * Replace all values
     *
     * @param array $values
     *
     * @return self
     */
    public function replaceValues(array $values): self
    {
        $this->values->replace($values);

        return $this;
    }


    /**
     * Validate the set
     *
     * @return Result
     */
    public function validate(): Result
    {
        $fields = [];

        foreach ($this->fields as $key => $field) {
            $field->clearFailedRules();
            if ($this->validateField($field) === false) {
                $fields[$field->getKey()] = $field;
            }
        }

        return new Result($fields, $this->templates);
    }


    /**
     * Validate a field
     *
     * @param Field $field
     *
     * @return bool
     */
    protected function validateField(Field $field): bool
    {
        $success = true;

        if ($this->values->has($field->getKey()) === false) {
            if ($field->isRequired() === true) {
                $field->addFailedRule('required', [], '{field} is required');
                return false;
            }

            return true;
        }

        if ($this->values->isNull($field->getKey())) {
            if ($field->isNullable() === false) {
                $field->addFailedRule('nullable', [], '{field} cannot be null');
                return false;
            }

            return true;
        }

        foreach ($field->rules->getRules() as $rule => $args) {
            // Fetch the validator
            $validator = $this->validators->get($rule, $this->values);

            // Execute the validator
            $isValid = call_user_func_array(
                $validator,
                array_merge([$field->getValue()], $args)
            );

            if ($isValid === false) {
                // It failed, get (or create) the default error message
                $error = $validator instanceof ValidatorInterface
                    ? $validator->getTemplate()
                    : '{field} failed validation';

                $field->addFailedRule($rule, $args, $error);
                $success = false;
            }
        }

        return $success;
    }
}
