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
 * Validates that this value is loosely equal to another value.
 *
 * @package Aura.Filter
 *
 */
class EqualToValue
{
    /**
     *
     * Validates that this value is loosely equal to another value.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @param mixed $other_value The other value.
     *
     * @return bool True if the values are equal, false if not equal.
     *
     */
    public function __invoke($subject, $field, $other_value)
    {
        return $subject->$field == $other_value;
    }
}
