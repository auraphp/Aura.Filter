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
 * Validates that a value is less than than or equal to a maximum.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Max
{
    /**
     *
     * Sanitizes to maximum value if values is greater than max
     *
     * @param mixed $max The maximum valid value.
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function sanitize($object, $field, $max)
    {
        $value = $object->$field;
        if (! is_scalar($value)) {
            return false;
        }
        if ($value > $max) {
            $object->$field = $max;
        }

        return true;
    }
}
