<?php

namespace Jsl\Ensure\Validators;

use Jsl\Ensure\Components\Values;

class Formats
{
    /**
     * Check if a value is a valid email address
     *
     * @param mixed $value
     *
     * @return bool|string
     */
    public function isEmail(mixed $value): bool|string
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL, FILTER_NULL_ON_FAILURE) !== null
            ?: 'Invalid email address';
    }


    /**
     * Check if a value is a valid URL
     *
     * @param mixed $value
     *
     * @return bool|string
     */
    public function isUrl(mixed $value): bool|string
    {
        return filter_var($value, FILTER_VALIDATE_URL, FILTER_NULL_ON_FAILURE) !== null
            ?: 'Invalid URL';
    }


    /**
     * Check if a value is a valid MAC address
     *
     * @param mixed $value
     *
     * @return bool|string
     */
    public function isMac(mixed $value): bool|string
    {
        return filter_var($value, FILTER_VALIDATE_MAC, FILTER_NULL_ON_FAILURE) !== null
            ?: 'Invalid MAC address';
    }


    /**
     * Check if a value is a valid IP address
     *
     * @param mixed $value
     *
     * @return bool|string
     */
    public function isIp(mixed $value): bool|string
    {
        return filter_var($value, FILTER_VALIDATE_IP, FILTER_NULL_ON_FAILURE) !== null
            ?: 'Invalid IP address';
    }


    /**
     * Check if a value is a valid IPv4 address
     *
     * @param mixed $value
     *
     * @return bool|string
     */
    public function isIpv4(mixed $value): bool|string
    {
        return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_NULL_ON_FAILURE) !== null
            ?: 'Invalid IPv4 address';
    }


    /**
     * Check if a value is a valid IPv6 address
     *
     * @param mixed $value
     *
     * @return bool|string
     */
    public function isIpv6(mixed $value): bool|string
    {
        return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 | FILTER_NULL_ON_FAILURE) !== null
            ?: 'Invalid IPv6 address';
    }


    /**
     * Check if a string only contains alpha characters
     *
     * @param mixed $value
     *
     * @return bool|string
     */
    public function isAlpha(mixed $value): bool|string
    {
        return (is_string($value) && ctype_alpha($value))
            ?: 'Contains non-alpha characters';
    }


    /**
     * Check if a string only contains alpha numeric characters
     *
     * @param mixed $value
     *
     * @return bool|string
     */
    public function isAlphaNumeric(mixed $value): bool|string
    {
        return (is_string($value) && ctype_alnum($value))
            ?: 'Contains non-alpha numeric characters';
    }


    /**
     * Check if a string contains a hexadecimal number
     *
     * @param mixed $value
     *
     * @return bool|string
     */
    public function isHex(mixed $value): bool|string
    {
        return (is_string($value) && ctype_xdigit($value))
            ?: 'Must be hexadecimal';
    }


    /**
     * Check if a string contains an octal number
     *
     * @param mixed $value
     *
     * @return bool|string
     */
    public function isOct(mixed $value): bool|string
    {
        if (is_string($value) === false) {
            return 'Must be an octal number';
        }

        $dec = @octdec($value);
        $oct = $dec ? @decoct($dec) : null;

        return $oct == $value
            ?: "Must be an octal number";
    }
}
