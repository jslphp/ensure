<?php

use Jsl\Ensure\Validators\Arrays;
use Jsl\Ensure\Validators\Comparisons;
use Jsl\Ensure\Validators\Formats;
use Jsl\Ensure\Validators\Size;
use Jsl\Ensure\Validators\Strings;
use Jsl\Ensure\Validators\Types;

return [
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
];
