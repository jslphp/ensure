<?php

namespace Jsl\Ensure\Components;

class Field
{
    /**
     * @var string
     */
    public readonly string $key;

    /**
     * @var bool
     */
    public readonly bool $required;

    /**
     * @var bool
     */
    public readonly bool $nullable;

    /**
     * @var array
     */
    public readonly array $rules;


    /**
     * @param string $field
     * @param array $rules
     * @param array $overrideArgs
     */
    public function __construct(string $field, array $rules, array $overrideArgs = [])
    {
        $this->key = $field;
        $this->rules = $this->parse($rules, $overrideArgs);
    }


    /**
     * Parse rules
     *
     * @param array $rules
     * @param array $overrideArgs
     *
     * @return array
     */
    protected function parse(array &$rules, array $overrideArgs): array
    {
        $return = [];
        $meta = ['required', 'nullable'];

        $this->required = in_array('required', $rules);
        $this->nullable = in_array('nullable', $rules);

        foreach ($rules as $rule => $args) {
            $validator = $rule;
            if (is_numeric($rule)) {
                $validator = $args;
                $args = [];
            }

            if (in_array($validator, $meta)) {
                continue;
            }

            $replace = (array)($overrideArgs[$rule] ?? []);

            $return[$validator] = array_replace((array)$args, $replace);
        }

        return $return;
    }
}
