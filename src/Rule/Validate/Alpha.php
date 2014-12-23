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
namespace Aura\Filter\Rule;

/**
 *
 * Rule for alphabetic characters.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
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
    public function validate($object, $field)
    {
        $value = $object->$field;
        if (! is_scalar($value)) {
            return false;
        }

        return ctype_alpha($value);
    }

    /**
     *
     * Strips non-alphabetic characters from the value.
     *
     * @return bool Always true.
     *
     */
    public function sanitize($object, $field)
    {
        $object->$field = preg_replace('/[^a-z]/i', '', $object->$field);
        return true;
    }
}
