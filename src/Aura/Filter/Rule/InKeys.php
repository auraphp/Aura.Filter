<?php
namespace Aura\Filter\Rule;

/**
 * 
 * Validates that the value is a key in the list of allowed options.
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class InKeys extends AbstractRule
{
    protected $message = 'FILTER_IN_KEYS';
    
    /**
     * 
     * Validates that the value is a key in a given array.
     * 
     * Given an array (second parameter), the value (first parameter) must 
     * match at least one of the array keys.
     * 
     * @param array $array An array of key-value pairs; the value must match
     * one of the keys in this array.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    protected function validate($array)
    {
        return array_key_exists($this->getValue(), (array) $array);
    }
    
    // cannot fix the value
    protected function sanitize()
    {
        return false;
    }
}
