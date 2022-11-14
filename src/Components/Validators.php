<?php

namespace Jsl\Ensure\Components;

use Closure;
use Jsl\Ensure\Contracts\ResolverMiddlewareInterface;
use Jsl\Ensure\Contracts\ValidatorInterface;
use Jsl\Ensure\Exceptions\UnknownValidatorException;
use Jsl\Ensure\Middlewares\ResolverMiddleware;

class Validators
{
    /**
     * @var string|Closure|ValidatorInterface[]
     */
    protected array $index = [];

    /**
     * @var ResolverMiddlewareInterface
     */
    protected ResolverMiddlewareInterface $resolver;


    /**
     * @param bool $loadDefaults Defaults to false
     */
    public function __construct(bool $loadDefaults = false)
    {
        $this->resolver = new ResolverMiddleware;

        if ($loadDefaults) {
            $this->addMany(require __DIR__ . '/../Validators/defaults.php');
        }
    }


    /**
     * Set the resolver
     *
     * @param ResolverMiddleware $resolver
     *
     * @return self
     */
    public function setResolver(ResolverMiddleware $resolver): self
    {
        $this->resolver = $resolver;

        return $this;
    }


    /**
     * Add a validator
     *
     * @param string $name
     * @param string|Closure|ValidatorInterface $validator
     *
     * @return self
     */
    public function add(string $name, string|Closure|ValidatorInterface $validator): self
    {
        $this->index[$name] = $validator;

        return $this;
    }


    /**
     * Add an array of validatords
     *
     * @param array $validators
     *
     * @return self
     */
    public function addMany(array $validators): self
    {
        foreach ($validators as $name => $validator) {
            $this->add($name, $validator);
        }

        return $this;
    }


    /**
     * Check if a validator is added
     *
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        return key_exists($name, $this->index);
    }


    /**
     * Get a validator
     *
     * @param string $name
     *
     * @return callable
     */
    public function get(string $name): callable
    {
        if ($this->has($name) === false) {
            throw new UnknownValidatorException("Unknown validator: {$name}");
        }

        $validator = $this->index[$name];

        if (is_string($validator) && is_callable($validator) === false) {
            $this->index[$name] = $this->resolver->resolveClass($validator);
        }

        return $this->index[$name];
    }


    /**
     * Execute a rule
     *
     * @param Data $data
     * @param string $fieldKey
     * @param string $name
     * @param array $args
     *
     * @return array [$success<bool>, $message<string|null>]
     */
    public function execute(Data $data, string $fieldKey, string $name, array $args = []): array
    {
        $validator = $this->get($name);

        if ($validator instanceof ValidatorInterface) {
            $validator->setData($data);
        }

        $ruleArgs = array_merge([$data->get($fieldKey)], (array)$args);

        $success = $validator(...$ruleArgs);

        if ($success === false) {
            $message = is_object($validator) ? $validator->getMessage() : '{field} failed validation';

            return [false, $message];
        }

        return [true, null];
    }
}
