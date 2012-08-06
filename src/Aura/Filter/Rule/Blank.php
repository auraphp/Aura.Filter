<?php
namespace Aura\Filter\Rule;

/**
 * 
 * Validates that a value is blank (null, empty string, or string of only 
 * whitespace characters).
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class Blank extends AbstractRule
{
    protected $message = 'FILTER_BLANK';
    
    /**
     * 
     * Validates that the value is null, or is a string composed only of
     * whitespace.
     * 
     * Non-strings and non-nulls never validate as blank; this includes
     * integers, floats, numeric zero, boolean true and false, any array with
     * zero or more elements, and all objects and resources.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    protected function validate()
    {
        return $this->isBlank();
    }
    
    protected function sanitize()
    {
        $this->setValue(null);
        return true;
    }
}
