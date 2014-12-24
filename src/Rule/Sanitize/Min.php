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
 * Validates that a value is greater than or equal to a minimum.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Min
{
    /**
     *
     * Check whether the value is less than min, if so set to min
     *
     * @param int $min
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function sanitize($object, $field, $min)
    {
        $value = $object->$field;
        if (! is_scalar($value)) {
            return false;
        }
        if ($value < $min) {
            $object->$field = $min;
        }

        return true;
    }
}
