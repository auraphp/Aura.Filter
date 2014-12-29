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
     * Validates that the value is less than than or equal to a maximum.
     *
     * @param mixed $max The maximum valid value.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function __invoke($object, $field, $max)
    {
        $value = $object->$field;
        if (! is_scalar($value)) {
            return false;
        }

        return $value <= $max;
    }
}
