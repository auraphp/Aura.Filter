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
 * Forces the value to a string, optionally applying `str_replace()`.
 *
 * @package Aura.Filter
 *
 */
class Str
{
    /**
     *
     * Forces the value to a string, optionally applying `str_replace()`.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @param string|array $find Find this/these in the value.
     *
     * @param string|array $replace Replace with this/these in the value.
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function __invoke($subject, $field, $find = null, $replace = null)
    {
        $value = (string) $subject->$field;
        if ($find || $replace) {
            $value = str_replace($find, $replace, $value);
        }
        $subject->$field = $value;
        return true;
    }
}
