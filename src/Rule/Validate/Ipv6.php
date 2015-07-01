<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Rule\Validate;

/**
 *
 * Validates a value is an IPv6 address.
 *
 * @package Aura.Filter
 *
 */
class Ipv6
{
    /**
     *
     * Validates that the value is a legal IPv6 address.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function __invoke($subject, $field)
    {
        $value = $subject->$field;

        // This validates without regard to reserved or private ranges in v6
        if (filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
            return false;
        } else {
            return true;
        }
    }
}
