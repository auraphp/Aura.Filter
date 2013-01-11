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

use Aura\Filter\AbstractRule;

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
class StrictEqualToField extends AbstractRule
{
    /**
     *
     * Error message
     * 
     * @var string
     */
    protected $message = 'FILTER_STRICT_EQUAL_TO_FIELD';

    /**
     * 
     * Validates that this value is equal in value and type to some other 
     * element in the filter chain.
     * 
     * If the other element does not exist in $this->data, or is null, the
     * validation will fail.
     * 
     * @param string $other_field Check against the value of this element in
     * $this->data.
     * 
     * @return bool True if the values are equal, false if not equal.
     * 
     */
    public function validate($other_field)
    {
        // the other field needs to exist and *not* be null
        if (! isset($this->data->$other_field)) {
            return false;
        }

        return $this->getValue() === $this->data->$other_field;
    }

    /**
     * 
     * force the field to the value of the other field
     * 
     * @param string $other_field
     * 
     * @return boolean
     */
    public function sanitize($other_field)
    {
        // the other field needs to exist and *not* be null
        if (! isset($this->data->$other_field)) {
            return false;
        }

        $this->setValue($this->data->$other_field);
        return true;
    }
}
