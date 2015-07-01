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
 * Rule for alphanumeric characters.
 *
 * @package Aura.Filter
 *
 */
class Alnum
{
    /**
     *
     * Validates that the value is only letters (upper/lower case) and digits.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function __invoke($subject, $field)
    {
        $value = $subject->$field;
        if (! is_scalar($value)) {
            return false;
        }

        return ctype_alnum((string) $value);
    }
}
