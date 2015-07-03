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
 * Applies `preg_replace()` to the value.
 *
 * @package Aura.Filter
 *
 */
class Regex
{
    /**
     *
     * Applies `preg_replace()` to the value.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @param string $expr The regular expression pattern to apply.
     *
     * @param string $replace Replace the found pattern with this string.
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function __invoke($subject, $field, $expr, $replace)
    {
        $value = $subject->$field;
        if (! is_scalar($value)) {
            return false;
        }
        $subject->$field = preg_replace($expr, $replace, $value);
        return true;
    }
}
