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
