<?php
namespace Aura\Filter\Rule;

/**
 * 
 * Forces a value to a string, no encoding or escaping.
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class String extends AbstractRule
{
    protected $message = 'FILTER_STRING';
    
    /**
     * 
     * Validates that the value can be represented as a string.
     * 
     * Essentially, this means any scalar value is valid (no arrays, objects,
     * resources, etc).
     * 
     * @return bool True if valid, false if not.
     * 
     * @todo allow for __toString() implementations
     * 
     */
    protected function validate()
    {
        return is_scalar($this->getValue());
    }
    
    /**
     * 
     * Forces the value to a string, optionally applying str_replace().
     * 
     * @return bool True if the value was fixed, false if not.
     * 
     */
    protected function sanitize($find = null, $replace = null)
    {
        $value = (string) $this->getValue();
        if ($find || $replace) {
            $value = str_replace($find, $replace, $value);
        }
        $this->setValue($value);
    }
}
