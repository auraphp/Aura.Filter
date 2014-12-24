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
namespace Aura\Filter\Rule\Sanitize;

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
