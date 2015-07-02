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
 * Validates that a value is greater than or equal to a minimum.
 *
 * @package Aura.Filter
 *
 */
class Min
{
    /**
     *
     * Validates that the value is greater than or equal to a minimum.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @param mixed $min The minimum valid value.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function __invoke($subject, $field, $min)
    {
        $value = $subject->$field;
        if (! is_scalar($value)) {
            return false;
        }

        return $value >= $min;
    }
}
