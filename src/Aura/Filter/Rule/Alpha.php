<?php
namespace Aura\Filter\Rule;

/**
 * 
 * Rule for alphabetic characters.
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class Alpha extends AbstractRule
{
    protected $message = 'FILTER_ALPHA';
    
    /**
     * 
     * Validates that the value is letters only (upper or lower case).
     * 
     * @return bool True if valid, false if not.
     * 
     */
    protected function validate()
    {
        return ctype_alpha($this->getValue());
    }
    
    /**
     * 
     * Strips non-alphabetic characters from the value.
     * 
     * @return bool True if the value was fixed, false if not.
     * 
     */
    protected function sanitize()
    {
        $this->setValue(preg_replace('/[^a-z]/i', '', $this->getValue()));
        return true;
    }
}
