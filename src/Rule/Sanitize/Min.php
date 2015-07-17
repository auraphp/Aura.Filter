<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Rule\Sanitize;

/**
 *
 * Sanitizes to minimum value if value is less than min.
 *
 * @package Aura.Filter
 *
 */
class Min
{
    /**
     *
     * Sanitizes to minimum value if value is less than min.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @param int $min The minimum value.
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function __invoke($subject, $field, $min)
    {
        $value = $subject->$field;
        if (! is_scalar($value)) {
            return false;
        }
        if ($value < $min) {
            $subject->$field = $min;
        }
        return true;
    }
}
