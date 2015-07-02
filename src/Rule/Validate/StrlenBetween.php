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
 * Validates that a value's length is within a given range.
 *
 * @package Aura.Filter
 *
 */
class StrlenBetween
{
    /**
     *
     * Validates that the length of the value is within a given range.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @param mixed $min The minimum valid length.
     *
     * @param mixed $max The maximum valid length.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function __invoke($subject, $field, $min, $max)
    {
        $value = $subject->$field;
        if (! is_scalar($value)) {
            return false;
        }
        $len = strlen($value);

        return ($len >= $min && $len <= $max);
    }
}
