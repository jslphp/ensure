<?php

namespace Jsl\Ensure\Validators;

class Formats
{
    /**
     * Check if a value is a valid email address
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function isEmail(mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL, FILTER_NULL_ON_FAILURE) !== null;
    }


    /**
     * Check if a value is a valid URL
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function isUrl(mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_URL, FILTER_NULL_ON_FAILURE) !== null;
    }


    /**
     * Check if a value is a valid MAC address
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function isMac(mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_MAC, FILTER_NULL_ON_FAILURE) !== null;
    }


    /**
     * Check if a value is a valid IP address
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function isIp(mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_IP, FILTER_NULL_ON_FAILURE) !== null;
    }


    /**
     * Check if a value is a valid IPv4 address
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function isIpv4(mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_NULL_ON_FAILURE) !== null;
    }


    /**
     * Check if a value is a valid IPv6 address
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function isIpv6(mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 | FILTER_NULL_ON_FAILURE) !== null;
    }


    /**
     * Check if a string only contains alpha characters
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function isAlpha(mixed $value): bool
    {
        return (is_string($value) && ctype_alpha($value));
    }


    /**
     * Check if a string only contains alpha numeric characters
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function isAlphaNumeric(mixed $value): bool
    {
        return (is_string($value) && ctype_alnum($value));
    }


    /**
     * Check if a string contains a hexadecimal number
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function isHex(mixed $value): bool
    {
        return (is_string($value) && ctype_xdigit($value));
    }


    /**
     * Check if a string contains an octal number
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function isOct(mixed $value): bool
    {
        if (is_string($value) === false) {
            return false;
        }

        $dec = @octdec($value);
        $oct = $dec ? @decoct($dec) : null;

        return $oct == $value;
    }
}
