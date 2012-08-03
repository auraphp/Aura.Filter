<?php
namespace Aura\Filter\Rule;

/**
 * 
 * Rule for alphanumeric characters.
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class Alnum extends AbstractRule
{
    protected $message = 'FILTER_ALNUM';
    
    /**
     * 
     * Validates that the value is only letters (upper/lower case) and digits.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    protected function validate()
    {
        return ctype_alnum((string) $this->getValue());
    }
    
    /**
     * 
     * Strips non-alphanumeric characters from the value.
     * 
     * @return bool True if the value was fixed, false if not.
     * 
     */
    protected function sanitize()
    {
        $this->setValue(preg_replace('/[^a-z0-9]/i', '', $this->getValue()));
        return true;
    }
}
