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
 * Validates that a value is blank (null, empty string, or string of only
 * whitespace characters).
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Blank
{
    /**
     *
     * Validates that the value is null, or is a string composed only of
     * whitespace.
     *
     * Non-strings and non-nulls never validate as blank; this includes
     * integers, floats, numeric zero, boolean true and false, any array with
     * zero or more elements, and all objects and resources.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function __invoke($object, $field)
    {
        // not set, or null, means it is blank
        if (! isset($object->$field) || $object->$field === null) {
            return true;
        }

        // non-strings are not blank: int, float, object, array, resource, etc
        if (! is_string($object->$field)) {
            return false;
        }

        // strings that trim down to exactly nothing are blank
        return trim($object->$field) === '';
    }
}
