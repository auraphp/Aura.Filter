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
 * Validates the value against a regular expression.
 *
 * @package Aura.Filter
 *
 */
class Regex
{
    /**
     *
     * Validates the value against a regular expression.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @param string $expr The regular expression to validate against.
     *
     * @return bool True if the value matches the expression, false if not.
     *
     */
    public function __invoke($subject, $field, $expr)
    {
        $value = $subject->$field;
        if (! is_scalar($value)) {
            return false;
        }
        return (bool) preg_match($expr, $value);
    }
}
