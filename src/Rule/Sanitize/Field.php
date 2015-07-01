<?php
/**
 *
 * This file is part of Aura for PHP.
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
    public function __invoke($subject, $field, $other_field)
    {
        // the other field needs to exist and *not* be null
        if (! isset($subject->$other_field)) {
            return false;
        }
        $subject->$field = $subject->$other_field;
        return true;
    }
}
