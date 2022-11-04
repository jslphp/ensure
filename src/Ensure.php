<?php

namespace Jsl\Ensure;

use Jsl\Ensure\Components\Field;
use Jsl\Ensure\Components\Registry;
use Jsl\Ensure\Components\Result;
use Jsl\Ensure\Components\Values;
use Jsl\Ensure\Exceptions\UnknownRulesetException;
use Jsl\Ensure\Validators\Arrays;
use Jsl\Ensure\Validators\Comparisons;
use Jsl\Ensure\Validators\Formats;
use Jsl\Ensure\Validators\Size;
use Jsl\Ensure\Validators\Strings;
use Jsl\Ensure\Validators\Types;

class Ensure
{
    /**
     * @var Registry
     */
    public readonly Registry $registry;

    /**
     * @var array
     */
    protected array $rulesets = [];


    public function __construct()
    {
        $this->registry = new Registry;
        $this->addDefaultValidators();
    }


    /**
     * Add a named ruleset
     *
     * @param string $name
     * @param array $rules
     * @param array $messages
     *
     * @return self
     */
    public function addRuleset(string $name, array $rules, array $messages = []): self
    {
        $this->rulesets[$name] = [
            'rules' => $rules,
            'messages' => $messages,
        ];

        return $this;
    }


    /**
     * Validate a data set against a rule set
     *
     * @param array $data
     * @param array|string $ruleset
     * @param array $messages
     *
     * @return Result
     */
    public function validate(array $data, array|string $ruleset, array $messages = []): Result
    {
        $values = new Values($data);
        $result = new Result;

        if (is_string($ruleset)) {
            // Get a named ruleset and messages
            if (key_exists($ruleset, $this->rulesets) === false) {
                throw new UnknownRulesetException("Unkown ruleset: {$ruleset}");
            }

            $set = $this->rulesets[$ruleset];
            $ruleset = $set['rules'];
            $messages = array_replace($set['messages'], $messages);
        }

        foreach ($ruleset as $field => $rules) {;
            [$success, $error] = $this->validateField(new Field($field, $rules), $values);

            if ($success) {
                // Successful, continue to next rule
                continue;
            }

            $result->setFieldAsFailed($field, $messages[$field] ?? ($error ?? null));
        }

        return $result;
    }


    /**
     * Validate a field
     *
     * @param Field $field
     * @param Values $values
     *
     * @return bool
     */
    public function validateField(Field $field, Values $values): array
    {
        if ($values->has($field->key) === false) {
            $success = $field->required === false;
            return [$success, $success === false ? 'is required' : null];
        }

        $value = $values->get($field->key);

        if ($value === null) {
            $success = $field->nullable;
            return [$success, $success === false ? 'cannot be null' : null];
        }

        $success = true;
        $error = null;
        foreach ($field->rules as $validator => $args) {
            $args = array_merge([$value], [$args]);

            $response = $this->registry->execute($validator, $args, $values);

            if ($response === true) {
                continue;
            }

            if (is_string($response)) {
                $error = $response;
            }

            $success = false;
            break;
        }

        return [$success, $error];
    }


    /**
     * @return void
     */
    protected function addDefaultValidators(): void
    {
        // Add validators
        $this->registry->batchAdd([
            // Format validators
            'email' => [Formats::class, 'isEmail'],
            'url' => [Formats::class, 'isUrl'],
            'mac' => [Formats::class, 'isMac'],
            'ip'  => [Formats::class, 'isIp'],
            'ipv4' => [Formats::class, 'isIpv4'],
            'ipv6' => [Formats::class, 'isIpv6'],
            'alpha' => [Formats::class, 'isAlpha'],
            'alphanum' => [Formats::class, 'isAlphaNumeric'],
            'hex' => [Formats::class, 'isHex'],
            'octal' => [Formats::class, 'isOctal'],

            // Type validators
            'string' => [Types::class, 'isString'],
            'integer' => [Types::class, 'isInt'],
            'numeric' => [Types::class, 'isNumeric'],
            'float' => [Types::class, 'isFloat'],
            'array' => [Types::class, 'isArray'],
            'boolean' => [Types::class, 'isBool'],

            // String validators
            'startsWith' => [Strings::class, 'startsWidth'],
            'notStartsWith' => [Strings::class, 'notStartsWidth'],
            'endsWith' => [Strings::class, 'endsWidth'],
            'notEndsWith' => [Strings::class, 'notEndsWidth'],
            'contains' => [Strings::class, 'contains'],
            'notContains' => [Strings::class, 'notContains'],
            'regex' => [Strings::class, 'regex'],

            // Array validators
            'in' => [Arrays::class, 'in'],
            'notIn' => [Arrays::class, 'notIn'],

            // Size validators
            'size' => [Size::class, 'size'],
            'minSize' => [Size::class, 'minSize'],
            'maxSize' => [Size::class, 'maxSize'],

            // Comparisons
            'same' => [Comparisons::class, 'same'],
            'notSame' => [Comparisons::class, 'notSame'],
        ]);
    }
}
