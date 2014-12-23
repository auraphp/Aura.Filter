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
 * Validates that a value is no longer than a certain length.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class StrlenMax
{
    /**
     *
     * Validates that a string is no longer than a certain length.
     *
     * @param mixed $max The value must have no more than this many
     *                   characters.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function validate($object, $field, $max)
    {
        $value = $object->$field;
        if (! is_scalar($value)) {
            return false;
        }

        return strlen($value) <= $max;
    }

    /**
     *
     * If the value is greater than max, set to max value
     *
     * @param int $max
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function sanitize($object, $field, $max)
    {
        $value = $object->$field;
        if (! is_scalar($value)) {
            return false;
        }
        if (strlen($value) > $max) {
            $object->$field = substr($value, 0, $max);
        }

        return true;
    }
}
