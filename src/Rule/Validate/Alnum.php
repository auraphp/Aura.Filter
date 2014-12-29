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
 * Rule for alphanumeric characters.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
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
    public function __invoke($object, $field)
    {
        $value = $object->$field;
        if (! is_scalar($value)) {
            return false;
        }

        return ctype_alnum((string) $value);
    }
}
