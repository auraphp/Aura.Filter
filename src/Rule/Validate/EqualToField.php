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
 * Validates that this value is loosely equal to some other subject field.
 *
 * @package Aura.Filter
 *
 */
class EqualToField
{
    /**
     *
     * Validates that this value is loosely equal to some other subject field.
     *
     * If the other element does not exist in $subject, or is null, the
     * validation will fail.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @param string $other_field Check against the value of this subject field.
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

        return $subject->$field == $subject->$other_field;
    }
}
