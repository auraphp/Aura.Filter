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
 * Sanitizes a value to a string using trim().
 *
 * @package Aura.Filter
 *
 */
class Trim
{
    /**
     *
     * Is the value already trimmed?
     *
     * @param string $chars The characters to strip.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function __invoke($subject, $field, $chars = " \t\n\r\0\x0B")
    {
        $value = $subject->$field;
        if (! is_scalar($value)) {
            return false;
        }
        return trim($value, $chars) == $value;
    }
}
