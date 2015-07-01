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
 * Rule for alphabetic characters.
 *
 * @package Aura.Filter
 *
 */
class Alpha
{
    /**
     *
     * Validates that the value is letters only (upper or lower case).
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

        return ctype_alpha($value);
    }
}
