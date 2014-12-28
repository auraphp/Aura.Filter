<?php
/**
 *
 * This file is part of the Aura project for PHP.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Rule\Validate;

/**
 *
 * Validates a value using preg_match(), and sanitizes a value to a string
 * using preg_replace().
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Regex
{
    /**
     *
     * Validates the value against a regular expression.
     *
     * Uses `preg_match()` to compare the value against the given
     * regular expression.
     *
     * @param string $expr The regular expression to validate against.
     *
     * @return bool True if the value matches the expression, false if not.
     *
     */
    public function __invoke($object, $field, $expr)
    {
        $value = $object->$field;
        if (! is_scalar($value)) {
            return false;
        }
        return (bool) preg_match($expr, $value);
    }
}
