<?php
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
class EqualToField extends AbstractRule
{
    protected $message = 'FILTER_EQUAL_TO_FIELD';
    
    /**
     * 
     * Validates that this value is equal to some other element in the filter 
     * chain (note that equality is *not* strict, so type does not matter).
     * 
     * If the other element does not exist in $this->data, or is null, the
     * validation will fail.
     * 
     * @param string $other_field Check against the value of this element in
     * $this->data.
     * 
     * @return bool True if the values are equal, false if not equal.
     * 
     * @return bool True if the values are equal, false if not equal.
     * 
     */
    protected function validate($other_field)
    {
        // the other field needs to exist and *not* be null
        if (! isset($this->data->$other_field)) {
            return false;
        }
        
        return $this->getValue() == $this->data->$other_field;
    }
    
    // force the field to the value of the other field
    protected function sanitize($other_field)
    {
        // the other field needs to exist and *not* be null
        if (! isset($this->data->$other_field)) {
            return false;
        }
        
        $this->setValue($this->data->$other_field);
        return true;
    }
}
