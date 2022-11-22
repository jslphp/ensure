<?php

namespace Jsl\Ensure\Validators\Types;

use InvalidArgumentException;
use Jsl\Ensure\Abstracts\Validator;
use Jsl\Ensure\Validators\Types\ArrayValidator as TypesArrayValidator;

class TypeValidator extends Validator
{
    /**
     * @inheritDoc
     */
    protected string $template = '{field} must be an array';

    /**
     * @var array
     */
    protected array $types = [
        'string'  => StringValidator::class,
        'numeric' => NumericValidator::class,
        'integer' => IntegerValidator::class,
        'decimal' => DecimalValidator::class,
        'boolean' => BoolValidator::class,
        'array'   => TypesArrayValidator::class,
    ];


    /**
     * @param mixed $value
     * @param string $type
     *
     * @return bool
     */
    public function __invoke(mixed $value, mixed $type): bool
    {
        $type = strtolower($type);
        $type = $type === 'bool' ? 'boolean' : $type;
        $type = $type === 'float' ? 'decimal' : $type;
        $type = $type === 'int' ? 'integer' : $type;

        if (key_exists($type, $this->types) === false) {
            throw new InvalidArgumentException("Invalid type for the type validator. Got: {$type}");
        }

        $validator = $this->types[$type];

        if (is_object($validator) === false) {
            $validator = $this->types[$type] = new $validator;
        }

        if ($validator($value) === false) {
            $this->template = $validator->getMessage();
            return false;
        }

        return true;
    }
}
