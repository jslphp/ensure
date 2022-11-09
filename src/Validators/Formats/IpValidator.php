<?php

namespace Jsl\Ensure\Validators\Formats;

use InvalidArgumentException;
use Jsl\Ensure\Abstracts\Validator;

class IpValidator extends Validator
{
    /**
     * @inheritDoc
     */
    protected string $message = '{field} must be a valid IP address';


    /**
     * @param mixed $value
     * @param mixed $type Defaults to 'any', but can also be 'ipv4' or 'ipv6'
     *
     * @return bool
     */
    public function __invoke(mixed $value, mixed $type = 'any'): bool
    {
        $type = is_null($type) ? 'any' : $type;
        $type = strtolower(is_string($type) ? $type : null);

        switch ($type) {
            case 'any':
                $this->message = '{field} must be a valid IP address';
                return is_string($value)
                    && filter_var($value, FILTER_VALIDATE_IP, FILTER_NULL_ON_FAILURE) !== null;
            case 'ipv4':
                $this->message = '{field} must be a valid IPv4 address';
                return is_string($value)
                    && filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_NULL_ON_FAILURE) !== null;
            case 'ipv6':
                $this->message = '{field} must be a valid IPv6 address';
                return is_string($value)
                    && filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 | FILTER_NULL_ON_FAILURE) !== null;
        }

        throw new InvalidArgumentException("IP format to validate against must be either 'ipv4', 'ipv6', 'any' or omitted.");
    }
}
