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
namespace Aura\Filter\Rule;

/**
 *
 * Forces a value to a string, no encoding or escaping.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class String
{
    /**
     *
     * Validates that the value can be represented as a string.
     *
     * Essentially, this means any scalar value is valid (no arrays, objects,
     * resources, etc).
     *
     * @return bool True if valid, false if not.
     *
     * @todo allow for __toString() implementations
     *
     */
    public function validate($object, $field)
    {
        return is_scalar($object->$field);
    }

    /**
     *
     * Forces the value to a string, optionally applying str_replace().
     *
     * @param string $find
     *
     * @param string $replace
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function sanitize($object, $field, $find = null, $replace = null)
    {
        $value = (string) $object->$field;
        if ($find || $replace) {
            $value = str_replace($find, $replace, $value);
        }
        $object->$field = $value;

        return true;
    }
}
