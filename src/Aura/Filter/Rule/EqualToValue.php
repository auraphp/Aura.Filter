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
class EqualToValue extends AbstractRule
{
    protected $message = 'FILTER_EQUAL_TO_VALUE';
    
    protected function validate($other_value)
    {
        return $this->getValue() == $other_value;
    }
    
    // force the field to the value of the other field
    protected function sanitize($other_value)
    {
        $this->setValue($other_value);
        return true;
    }
}
