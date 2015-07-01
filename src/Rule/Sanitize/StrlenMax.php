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
 * Validates that a value is no longer than a certain length.
 *
 * @package Aura.Filter
 *
 */
class StrlenMax
{
    /**
     *
     * If the value is greater than max, set to max value
     *
     * @param int $max
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function __invoke($subject, $field, $max)
    {
        $value = $subject->$field;
        if (! is_scalar($value)) {
            return false;
        }
        if (strlen($value) > $max) {
            $subject->$field = substr($value, 0, $max);
        }
        return true;
    }
}
