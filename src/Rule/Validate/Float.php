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
 * Rule for floats.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Float
{
    /**
     *
     * Validates that the value represents a float.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function __invoke($object, $field)
    {
        $value = $object->$field;

        if (is_float($value)) {
            return true;
        }

        // otherwise, must be numeric, and must be same as when cast to float
        return is_numeric($value) && $value == (float) $value;
    }
}
