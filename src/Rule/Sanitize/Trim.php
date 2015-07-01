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
 * Sanitizes a value to a string using trim().
 *
 * @package Aura.Filter
 *
 */
class Trim
{
    /**
     *
     * Trims characters from the beginning and end of the value.
     *
     * @param string $chars The characters to strip.
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function __invoke($subject, $field, $chars = " \t\n\r\0\x0B")
    {
        $value = $subject->$field;
        if (is_scalar($value) || $value === null) {
            $subject->$field = trim($value, $chars);
            return true;
        }
        return false;
    }
}
