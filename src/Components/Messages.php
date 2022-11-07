<?php

namespace Jsl\Ensure\Components;

class Messages
{
    /**
     * Create list of messages for rule errors
     *
     * @param array $input
     * @param array $messages
     *
     * @return array
     */
    public function create(array $input, array $messages, array $names = []): array
    {
        if (isset($input[0])) {
            // It's a list of fields, parse those
            return $this->fields($input, $messages, $names);
        }

        $list = [];
        foreach ($input as $field => $rules) {
            $list[$field] = [];
            $name = $names[$field] ?? $field;

            foreach ($rules as $rule) {
                $msg = $messages[$field][$rule->rule] ?? null;

                if (is_string($msg)) {
                    $list[$field][] = $this->replaceDynamicValues($msg, $name, $rule->args);
                }
            }

            if (empty($list[$field]) && isset($messages[$field]['default'])) {
                $list[$field] = $this->replaceDynamicValues(
                    $messages[$field]['default'],
                    $name
                );
            }
        }

        return $list;
    }


    /**
     * Create list of messages for field errros
     *
     * @param array $input
     * @param array $messages
     * @param array $names
     *
     * @return array
     */
    protected function fields(array $input, array $messages, array $names = []): array
    {
        $list = [];
        foreach ($input as $field) {
            if (isset($messages[$field]['default']) === false) {
                continue;
            }

            $name = $names[$field] ?? $field;

            $list[$field] = $this->replaceDynamicValues($messages[$field]['default'], $name);
        }

        return $list;
    }


    /**
     * Replace placeholders
     *
     * @param string $message
     * @param string $field
     * @param array $args
     * @param string|null $name
     *
     * @return string
     */
    protected function replaceDynamicValues(string $message, string $field, array $args = []): string
    {
        $message = str_replace('{field}', $field, $message);
        $replace = array_map(fn ($i) => "{a:{$i}}", range(0, count($args)));

        return str_replace($replace, $args, $message);
    }
}
