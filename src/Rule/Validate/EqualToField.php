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
class EqualToField
{
    /**
     *
     * Validates that this value is equal to some other element in the filter
     * chain (note that equality is *not* strict, so type does not matter).
     *
     * If the other element does not exist in $this->data, or is null, the
     * validation will fail.
     *
     * @param string $other_field Check against the value of this element in
     *                            $this->data.
     *
     * @return bool True if the values are equal, false if not equal.
     *
     */
    public function validate($other_field)
    {
        $this->setParams(get_defined_vars());

        // the other field needs to exist and *not* be null
        if (! isset($this->data->$other_field)) {
            return false;
        }

        return $this->getValue() == $this->data->$other_field;
    }

    /**
     *
     * Force the field to the value of the other field
     *
     * @param string $other_field
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function sanitize($other_field)
    {
        $this->setParams(get_defined_vars());

        // the other field needs to exist and *not* be null
        if (! isset($this->data->$other_field)) {
            return false;
        }

        $this->setValue($this->data->$other_field);

        return true;
    }
}
