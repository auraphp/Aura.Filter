<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @package Aura.Filter
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
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class StrictEqualToField
{
    /**
     *
     * Validates that this value is equal in value and type to some other
     * element in the filter chain.
     *
     * If the other element does not exist in $subject, or is null, the
     * validation will fail.
     *
     * @param string $other_field Check against the value of this element in
     * $subject.
     *
     * @return bool True if the values are equal, false if not equal.
     *
     */
    public function __invoke($subject, $field, $other_field)
    {
        // the other field needs to exist and *not* be null
        if (! isset($subject->$other_field)) {
            return false;
        }

        return $subject->$field === $subject->$other_field;
    }
}
