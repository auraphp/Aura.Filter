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
 * Validates that a value is within a given range.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Between
{
    /**
     *
     * Validates that the value is within a given range.
     *
     * @param mixed $min The minimum valid value.
     *
     * @param mixed $max The maximum valid value.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function validate($object, $field, $min, $max)
    {

        $value = $object->$field;

        if (! is_scalar($value)) {
            return false;
        }

        return ($value >= $min && $value <= $max);
    }

    /**
     *
     * If the value is < min , will set the min value,
     * and if value is greater than max, set the max value
     *
     * @param mixed $min The minimum valid value.
     *
     * @param mixed $max The maximum valid value.
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function sanitize($object, $field, $min, $max)
    {
        $value = $object->$field;

        if (! is_scalar($value)) {
            return false;
        }

        if ($value < $min) {
            $object->$field = $min;
        } elseif ($value > $max) {
            $object->$field = $max;
        }

        return true;
    }
}
