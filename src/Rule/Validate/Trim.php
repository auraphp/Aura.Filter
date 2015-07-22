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
 * Validates that a value is already trimmed.
 *
 * @package Aura.Filter
 *
 */
class Trim
{
    /**
     *
     * Validates that a value is already trimmed.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
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
