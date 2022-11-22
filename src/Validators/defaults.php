<?php

use Jsl\Ensure\Validators\Arrays;
use Jsl\Ensure\Validators\Fields;
use Jsl\Ensure\Validators\Formats;
use Jsl\Ensure\Validators\Sizes;
use Jsl\Ensure\Validators\Types;

return [
    // Formats
    'alphanum'  => Formats\AlphaNumValidator::class,
    'alpha'     => Formats\AlphaValidator::class,
    'email'     => Formats\EmailValidator::class,
    'hex'       => Formats\HexValidator::class,
    'ip'        => Formats\IpValidator::class,
    'mac'       => Formats\MacValidator::class,
    'url'       => Formats\UrlValidator::class,
    // Sizes
    'size'      => Sizes\SizeValidator::class,
    'minSize'   => Sizes\MinSizeValidator::class,
    'maxSize'   => Sizes\MaxSizeValidator::class,
    // Arrays
    'in'        => Arrays\InValidator::class,
    'notIn'     => Arrays\NotInValidator::class,
    // Fields
    'same'      => Fields\SameValidator::class,
    'different' => Fields\DifferentValidator::class,
    // Type
    'type'      => Types\TypeValidator::class,
];
