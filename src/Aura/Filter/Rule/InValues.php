<?php
namespace Aura\Filter\Rule;

/**
 * 
 * Validates that a value is in a list of allowed values.
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class InValues extends AbstractRule
{
    protected $message = 'FILTER_IN_VALUES';
    
    /**
     * 
     * Validates that the value is in a given array.
     * 
     * Strict checking is enforced, so a string "1" is not the same as
     * an integer 1.  This helps to avoid matching between 0, false, null,
     * and empty string.
     * 
     * @param array $array An array of allowed values.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    protected function validate($array)
    {
        return in_array($this->getValue(), (array) $array, true);
    }
    
    // cannot fix the value
    protected function sanitize()
    {
        return false;
    }
}