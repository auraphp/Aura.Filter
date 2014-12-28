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
class EqualToValue
{
    /**
     *
     * Force the field to the value of the other field
     *
     * @param mixed $other_value
     *
     * @return bool Always true.
     *
     */
    public function __invoke($object, $field, $other_value)
    {
        $object->field = $other_value;
        return true;
    }
}
