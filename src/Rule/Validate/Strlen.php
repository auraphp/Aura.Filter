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
 * Validates that a value's length is within a given range.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Strlen
{
    /**
     *
     * Validates that the length of the value is within a given range.
     *
     * @param mixed $len The minimum valid length.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function __invoke($subject, $field, $len)
    {
        $value = $subject->$field;
        if (! is_scalar($value)) {
            return false;
        }

        return strlen($value) == $len;
    }
}
