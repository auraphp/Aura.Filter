<?php
declare(strict_types=1);

/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Aura\Filter\Rule\Sanitize;

/**
 *
 * Validates that a value is within a given range.
 *
 * @package Aura.Filter
 *
 */
class Between
{
    /**
     *
     * If the value is less than min, will set the min value,
     * and if value is greater than max, set the max value.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @param mixed $min The minimum valid value.
     *
     * @param mixed $max The maximum valid value.
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function __invoke(object $subject, string $field, $min, $max): bool
    {
        $value = $subject->$field;
        if (! is_scalar($value)) {
            return false;
        }
        if ($value < $min) {
            $subject->$field = $min;
        }
        if ($value > $max) {
            $subject->$field = $max;
        }
        return true;
    }
}
