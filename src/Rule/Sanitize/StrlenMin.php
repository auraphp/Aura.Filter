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
 * Validates that a value is no longer than a certain length.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class StrlenMin
{
    /**
     *
     * Fix to min length
     *
     * @param int $min
     *
     * @param string $pad_string
     *
     * @param int $pad_type
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function sanitize($object, $field, $min, $pad_string = ' ', $pad_type = STR_PAD_RIGHT)
    {
        $value = $object->$field;
        if (! is_scalar($value)) {
            return false;
        }
        if (strlen($value) < $min) {
            $object->$field = str_pad($value, $min, $pad_string, $pad_type);
        }

        return true;
    }
}
