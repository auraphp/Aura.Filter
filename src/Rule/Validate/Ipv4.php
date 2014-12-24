<?php
/**
 *
 * This file is part of the Aura project for PHP.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Rule\Validate;

/**
 *
 * Sanitizes a value to an IPv4 address.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Ipv4
{
    /**
     *
     * Validates that the value is a legal IPv4 address.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function validate($object, $field)
    {
        $value = $object->$field;

        // does the value convert back and forth properly?
        $result = ip2long($value);
        if ($result == -1 || $result === false) {
            // does not properly convert to a "long" result
            return false;
        } else {
            // looks valid
            return true;
        }
    }
}
