<?php

namespace Jsl\Ensure\Validators;

use Closure;
use Jsl\Ensure\Contracts\RegistryInterface;
use Jsl\Ensure\Contracts\ValidatorInterface;
use Jsl\Ensure\Entities\CallbackEntity;
use Jsl\Ensure\Exceptions\UnknownValidatorException;

class Registry implements RegistryInterface
{
    /**
     * @var CallbackEntity[]
     */
    protected array $index = [];


    /**
     * @inheritDoc
     */
    public function add(string $name, string|Closure|ValidatorInterface $validator): self
    {
        $this->index[$name] = new CallbackEntity($name, $validator);

        return $this;
    }


    /**
     * @inheritDoc
     */
    public function addMany(array $validators): self
    {
        foreach ($validators as $name => $validator) {
            $this->add($name, $validator);
        }

        return $this;
    }


    /**
     * @inheritDoc
     */
    public function has(string $name): bool
    {
        return key_exists($name, $this->index);
    }


    /**
     * @inheritDoc
     */
    public function get(string $name): CallbackEntity
    {
        if ($this->has($name) === false) {
            throw new UnknownValidatorException("Unknown validator: {$name}");
        }

        $entity = $this->index[$name];

        if ($entity->isResolved() === false) {
            $validator = $entity->getValidator();
            $entity->setValidator(new $validator());
        }

        return $entity;
    }
}
