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
 * Validates that this value is equal to some other element in the filter
 * chain (note that equality is not strict, so type does not matter).
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class StrictEqualToField
{
    /**
     *
     * Force the field to the value of the other field.
     *
     * @param string $other_field
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function sanitize($object, $field, $other_field)
    {
        // the other field needs to exist and *not* be null
        if (! isset($object->$other_field)) {
            return false;
        }

        $object->$field = $object->$other_field;
        return true;
    }
}
