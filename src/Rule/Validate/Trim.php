<?php
declare(strict_types=1);

/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
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
    public function __invoke(object $subject, string $field, string $chars = " \t\n\r\0\x0B"): bool
    {
        $value = $subject->$field;
        if (! is_scalar($value)) {
            return false;
        }
        return trim($value, $chars) == $value;
    }
}
