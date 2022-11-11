<?php

namespace Jsl\Ensure\Components;

use Jsl\Ensure\Contracts\ErrorsMiddlewareInterface;
use Jsl\Ensure\Middlewares\ErrorsMiddleware;

class Container
{
    /**
     * @var Validators|null
     */
    protected Validators|null $validators = null;

    /**
     * @var Data|null
     */
    protected Data|null $data = null;

    /**
     * @var Rulesets|null
     */
    protected Rulesets|null $rulesets = null;

    /**
     * @var ErrorsMiddlewareInterface
     */
    protected ErrorsMiddlewareInterface|null $errorsMiddleware = null;


    /**
     * Set the validators instance
     *
     * @param Validators $validators
     *
     * @return self
     */
    public function setValidators(Validators $validators): self
    {
        $this->validators = $validators;

        return $this;
    }


    /**
     * Check if the validators instance is set
     *
     * @return bool
     */
    public function hasValidatorsInstance(): bool
    {
        return $this->validators !== null;
    }


    /**
     * Get the validators instance (creates new if not already set)
     *
     * @return Validators
     */
    public function validators(): Validators
    {
        if ($this->validators === null) {
            $this->validators = new Validators(true);
        }

        return $this->validators;
    }


    /**
     * Set the data instance
     *
     * @param Data $data
     *
     * @return self
     */
    public function setData(Data $data): self
    {
        $this->data = $data;

        return $this;
    }


    /**
     * Check if the data instance is set
     *
     * @return bool
     */
    public function hasDataInstance(): bool
    {
        return $this->data !== null;
    }


    /**
     * Get the data instance (creates new if not already set)
     *
     * @return Data
     */
    public function data(): Data
    {
        if ($this->data === null) {
            $this->data = new Data([]);
        }

        return $this->data;
    }


    /**
     * Set the errors middlware instance
     *
     * @param ErrorsMiddlewareInterface $middleware
     *
     * @return self
     */
    public function setErrorsMiddleware(ErrorsMiddlewareInterface $middleware): self
    {
        $this->errorsMiddleware = $middleware;

        return $this;
    }


    /**
     * Check if the errors middleware instance is set
     *
     * @return bool
     */
    public function hasErrorsMiddlewareInstance(): bool
    {
        return $this->errorsMiddleware !== null;
    }


    /**
     * Get the errors middleware instance (creates new if not already set)
     *
     * @return ErrorsMiddlewareInterface
     */
    public function errorsMiddleware(): ErrorsMiddlewareInterface
    {
        if ($this->errorsMiddleware === null) {
            $this->errorsMiddleware = new ErrorsMiddleware;
        }

        return $this->errorsMiddleware;
    }


    /**
     * Set the Rulesets instance
     *
     * @param Rulesets $rulesets
     *
     * @return self
     */
    public function setRulesets(Rulesets $rulesets): self
    {
        $this->rulesets = $rulesets;

        return $this;
    }


    /**
     * Check if the Rulesets instance is set
     *
     * @return bool
     */
    public function hasRulesetsInstance(): bool
    {
        return $this->rulesets !== null;
    }


    /**
     * Get the Rulesets instance (creates new if not already set)
     *
     * @return Rulesets
     */
    public function rulesets(): Rulesets
    {
        if ($this->rulesets === null) {
            $this->rulesets = new Rulesets;
        }

        return $this->rulesets;
    }
}
