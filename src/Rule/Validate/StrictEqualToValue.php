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
 * Validates that this value is equal to some other element in the filter
 * chain (note that equality is not strict, so type does not matter).
 *
 * @package Aura.Filter
 *
 */
class StrictEqualToValue
{
    /**
     *
     * Check the values strictly on type and value
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @param string $other_value
     *
     * @return bool True if the values are equal, false if not equal.
     *
     */
    public function __invoke($subject, $field, $other_value)
    {
        return $subject->$field === $other_value;
    }
}
