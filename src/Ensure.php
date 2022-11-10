<?php

namespace Jsl\Ensure;

use Jsl\Ensure\Contracts\RegistryInterface;
use Jsl\Ensure\Data\Data;
use Jsl\Ensure\Entities\CallbackEntity;
use Jsl\Ensure\Entities\ErrorEntity;
use Jsl\Ensure\Entities\FieldEntity;
use Jsl\Ensure\Entities\ResultEntity;

class Ensure
{
    /**
     * @var RegistryInterface
     */
    protected RegistryInterface $registry;

    /**
     * @var Data
     */
    protected Data $data;

    /**
     * @var FieldEntity[]
     */
    protected array $fields = [];


    /**
     * @param RegistryInterface $registry
     * @param array $data
     * @param array $rules
     */
    public function __construct(RegistryInterface $registry, array $data, array $rules = [])
    {
        $this->registry = $registry;
        $this->data = new Data($data);
        $this->setRules($rules);
    }


    /**
     * Set the validation rules
     *
     * @param array $rules
     *
     * @return self
     */
    public function setRules(array $rules): self
    {
        $this->fields = [];
        $this->errors = [];

        foreach ($rules as $field => $fieldRules) {
            $this->fields[$field] = new FieldEntity($this->data, $field, $fieldRules);
        }

        return $this;
    }


    /**
     * Validate the data
     *
     * @return ResultEntity
     */
    public function validate(): ResultEntity
    {
        $success = true;
        $errors = [];

        foreach ($this->fields as $field) {
            $key = $field->getKey();

            if ($field->shouldBeSkipped()) {
                if ($field->isRequired() && $field->isMissing()) {
                    $message = '{field} is required';
                    $errors[$key][] = new ErrorEntity($field, 'required', [], $message);
                    $success = false;
                }

                if ($field->isNull() && $field->isNullable() === false) {
                    $message = '{field} cannot be null';
                    $errors[$key][] = new ErrorEntity($field, 'nullable', [], $message);
                    $success = false;
                }

                continue;
            }


            foreach ($field->getRules() as $rule => $args) {
                $entity = $this->prepareCallbackEntity($rule, $args);
                $ruleArgs = array_merge([$field->getValue()], $entity->getArguments());

                if ($entity->execute($ruleArgs) === false) {
                    $message = $entity->getMessage();
                    $errors[$key][] = new ErrorEntity($field, $rule, $entity->getArguments(), $message);
                    $success = false;
                }
            }
        }

        return new ResultEntity($success, $errors);
    }


    /**
     * Prepare the callback entity
     *
     * @param string $rule
     * @param array $args
     *
     * @return CallbackEntity
     */
    protected function prepareCallbackEntity(string $rule, array $args): CallbackEntity
    {
        $entity = $this->registry->get($rule);

        if ($entity->isValidatorInstance()) {
            $entity->getValidator()->setData($this->data);
        }

        $entity->setArguments($args);

        return $entity;
    }
}
