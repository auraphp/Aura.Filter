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
 * Modifies the field value to match that of another field.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Field
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
    public function __invoke($object, $field, $other_field)
    {
        // the other field needs to exist and *not* be null
        if (! isset($object->$other_field)) {
            return false;
        }
        $object->$field = $object->$other_field;
        return true;
    }
}
