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
 * Validates that the value is in a given array.
 *
 * @package Aura.Filter
 *
 */
class InValues
{
    /**
     *
     * Validates that the value is in a given array.
     *
     * Strict checking is enforced, so a string "1" is not the same as
     * an integer 1.  This helps to avoid matching between 0, false, null,
     * and empty string.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @param array $array An array of allowed values.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function __invoke($subject, $field, array $array)
    {
        return in_array($subject->$field, $array, true);
    }
}
