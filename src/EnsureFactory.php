<?php

namespace Jsl\Ensure;

use Jsl\Ensure\Components\Messages;
use Jsl\Ensure\Components\Registry;
use Jsl\Ensure\Components\Rulesets;

class EnsureFactory
{
    /**
     * @var Registry
     */
    protected Registry $registry;

    /**
     * @var Rulesets
     */
    protected Rulesets $rulesets;


    /**
     * @param Registry|null $registry
     */
    public function __construct(?Registry $registry = null)
    {
        $this->registry = $registry ?? new Registry;
        $this->rulesets = new Rulesets;

        // Register default validators
        $this->addValidators(require __DIR__ . '/Validators/defaults.php');
    }


    /**
     * Add a ruleset
     *
     * @param string $name
     * @param array $ruleset
     *
     * @return self
     */
    public function addRuleset(string $name, array $ruleset): self
    {
        $this->rulesets->add($name, $ruleset);

        return $this;
    }


    /**
     * Add a ruleset
     *
     * @param string $name
     *
     * @return array
     */
    public function getRuleset(string $name): array
    {
        return $this->rulesets->get($name);
    }


    /**
     * Add validator
     *
     * @param string $name
     * @param array|callable $callback
     *
     * @return self
     */
    public function addValidator(string $name, array|callable $callback): self
    {
        $this->registry->add($name, $callback);
        return $this;
    }


    /**
     * Add multiple validators at once
     *
     * @param array $validators Format: [validatorName => callable|[classname|instance, method]]
     *
     * @return self
     */
    public function addValidators(array $validators): self
    {
        foreach ($validators as $name => $validator) {
            $this->addValidator($name, $validator);
        }

        return $this;
    }


    /**
     * Create a new Ensure instance
     *
     * @param array $data
     * @param array|string $ruleset
     * @param array $fieldNames
     *
     * @return Ensure
     */
    public function create(array $data, string|array $ruleset = [], array $fieldNames = []): Ensure
    {
        return (new Ensure($data, $this->registry, $this->rulesets, $ruleset))
            ->setFieldNames($fieldNames);
    }
}
