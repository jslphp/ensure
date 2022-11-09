<?php

use Jsl\Ensure\Validators\Formats;
use Jsl\Ensure\Validators\Sizes;

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
];
