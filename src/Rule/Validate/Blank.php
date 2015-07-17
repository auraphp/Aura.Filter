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
 * Validates that a value is blank (null, empty string, or string of only
 * whitespace characters).
 *
 * @package Aura.Filter
 *
 */
class Blank
{
    /**
     *
     * Validates that a value is blank (null, empty string, or string of only
     * whitespace characters).
     *
     * Non-strings and non-nulls never validate as blank; this includes
     * integers, floats, numeric zero, boolean true and false, any array with
     * zero or more elements, and all objects and resources.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function __invoke($subject, $field)
    {
        // not set, or null, means it is blank
        if (! isset($subject->$field) || $subject->$field === null) {
            return true;
        }

        // non-strings are not blank: int, float, object, array, resource, etc
        if (! is_string($subject->$field)) {
            return false;
        }

        // strings that trim down to exactly nothing are blank
        return trim($subject->$field) === '';
    }
}
