<?php

namespace Jsl\Ensure\Middlewares;

use Jsl\Ensure\Contracts\ErrorsMiddlewareInterface;

class ErrorsMiddleware implements ErrorsMiddlewareInterface
{
    /**
     * @var string
     */
    protected string $fieldName;


    /**
     * Render errors from the failed rules
     *
     * @param string $fieldName
     * @param array $failedRules
     * @param array $customErrors
     *
     * @return string|array
     */
    public function renderErrors(string $fieldName, array $failedRules, array $customErrors = []): string|array
    {
        $this->fieldName = $fieldName;

        $errors = [];

        if (isset($customErrors['fields'][$fieldName])) {
            return $this->renderError([
                'message' => $customErrors['fields'][$fieldName],
                'args' => [],
            ]);
        }

        if (isset($customErrors['rules']) === false) {
            $customErrors['rules'] = [];
        }


        foreach ($failedRules as $rule => $info) {
            if (key_exists($rule, $customErrors['rules'])) {
                $info['message'] = $customErrors['rules'][$rule];
            }

            if ($message = $this->renderError($info)) {
                $errors[] = $message;
            }
        }

        return $errors;
    }


    /**
     * Render the error messages
     *
     * @param array $info
     *
     * @return string|null
     */
    protected function renderError(array $info): string|null
    {
        if (empty($info['message'])) {
            return null;
        }

        if (empty($info['args'])) {
            $info['args'] = [];
        }

        $replace = array_map(fn ($i) => '{a:' . $i . '}', range(0, count($info['args']) - 1));

        $message = str_replace('{field}', $this->fieldName, $info['message']);

        return str_replace($replace, $info['args'], $message);
    }
}
