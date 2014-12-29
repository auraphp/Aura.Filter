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
 * Validates that the value is a key in the list of allowed options.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class InKeys
{
    /**
     *
     * Validates that the value is a key in a given array.
     *
     * Given an array (second parameter), the value (first parameter) must
     * match at least one of the array keys.
     *
     * @param array $array An array of key-value pairs; the value must match
     *                     one of the keys in this array.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function __invoke($subject, $field, array $array)
    {
        $value = $subject->$field;
        if (! is_string($value) && ! is_int($value)) {
            // array_key_exists errors on non-string non-int keys.
            return false;
        }
        // using array_keys() converts string numeric keys to integers, which
        // is *not* the behavior we want.
        return array_key_exists($subject->$field, $array);
    }
}
