<?php

namespace Jsl\Ensure;

use Closure;
use Jsl\Ensure\Components\ErrorTemplates;
use Jsl\Ensure\Components\Rulesets;
use Jsl\Ensure\Components\Validators;
use Jsl\Ensure\Traits\SetTemplatesTrait;

class EnsureFactory
{
    use SetTemplatesTrait;

    /**
     * @var Validators
     */
    protected Validators $validators;

    /**
     * @var ErrorTemplates
     */
    protected ErrorTemplates $templates;

    /**
     * @var Rulesets
     */
    protected Rulesets $sets;

    /**
     * @var string
     */
    protected string $fieldSeparator = '.';


    public function __construct()
    {
        $this->validators = new Validators;
        $this->templates = new ErrorTemplates;
        $this->sets = new Rulesets;
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
     * Set the field separator for the values
     * - Default is .
     *
     * @param string $separator
     *
     * @return self
     */
    public function setFieldSeparator(string $separator): self
    {
        $this->fieldSeparator = $separator;

        return $this;
    }


    /**
     * Add a ruleset
     *
     * @param string $name
     * @param array $rules
     *
     * @return self
     */
    public function addRuleset(string $name, array $rules): self
    {
        $this->sets->add($name, $rules);

        return $this;
    }


    /**
     * Add a validator
     *
     * @param string $name
     * @param mixed $callback
     * @param string|null $template Optional error template for the validator
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
     * Add list of validators
     *
     * @param array $validators
     *
     * @return self
     */
    public function addValidators(array $validators): self
    {
        $this->validators->addMany($validators);

        return $this;
    }


    /**
     * Create a new Ensure instance
     *
     * @param array|string $rules
     * @param array $values
     *
     * @return Ensure
     */
    public function create(array|string $rules, array $values): Ensure
    {
        if (is_string($rules)) {
            $rules = $this->sets->get($rules);
        }

        $ensure = new Ensure($rules, $values, clone $this->validators, clone $this->templates);
        $ensure->setFieldSeparator($this->fieldSeparator);

        return $ensure;
    }
}
