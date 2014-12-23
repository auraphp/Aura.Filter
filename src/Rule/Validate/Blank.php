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
 * Validates that a value is blank (null, empty string, or string of only
 * whitespace characters).
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Blank
{
    /**
     *
     * Validates that the value is null, or is a string composed only of
     * whitespace.
     *
     * Non-strings and non-nulls never validate as blank; this includes
     * integers, floats, numeric zero, boolean true and false, any array with
     * zero or more elements, and all objects and resources.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function validate($object, $field)
    {
        return $this->isBlank();
    }

    /**
     *
     * Set value to null
     *
     * @return bool Always true.
     *
     */
    public function sanitize($object, $field)
    {
        $object->$field = null;
        return true;
    }
}
