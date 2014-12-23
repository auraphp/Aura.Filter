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
 * Validates that this value is equal to some other element in the filter
 * chain (note that equality is not strict, so type does not matter).
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class EqualToValue extends AbstractRule
{
    /**
     *
     * Check whether the value is equal
     *
     * @param mixed $other_value
     *
     * @return bool True if the values are equal, false if not equal.
     *
     */
    public function validate($other_value)
    {
        $this->setParams(get_defined_vars());

        return $this->getValue() == $other_value;
    }

    /**
     *
     * Force the field to the value of the other field
     *
     * @param mixed $other_value
     *
     * @return bool Always true.
     *
     */
    public function sanitize($other_value)
    {
        $this->setParams(get_defined_vars());
        $this->setValue($other_value);

        return true;
    }
}
