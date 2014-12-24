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
namespace Aura\Filter\Rule\Sanitize;

/**
 *
 * Validates a value using preg_match(), and sanitizes a value to a string
 * using preg_replace().
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Regex
{
    /**
     *
     * Applies [[php::preg_replace() | ]] to the value.
     *
     * @param string $expr The regular expression pattern to apply.
     *
     * @param string $replace Replace the found pattern with this string.
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function sanitize($object, $field, $expr, $replace)
    {
        $value = $object->$field;
        if (! is_scalar($value)) {
            return false;
        }
        $object->$field = preg_replace($expr, $replace, $value);

        return true;
    }
}
