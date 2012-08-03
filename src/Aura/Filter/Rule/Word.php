<?php
namespace Aura\Filter\Rule;

/**
 * 
 * Sanitizes a value to a string with only word characters.
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class Word extends AbstractRule
{
    protected $message = 'FILTER_WORD';
    
    /**
     * 
     * Validates that the value is composed only of word characters.
     * 
     * These include a-z, A-Z, 0-9, and underscore, indicated by a 
     * regular expression "\w".
     * 
     * @return bool True if valid, false if not.
     * 
     */
    protected function validate()
    {
        $expr = '/^\w+$/D';
        return (bool) preg_match($expr, $this->getValue());
    }
    
    /**
     * 
     * Strips non-word characters within the value.
     * 
     * @return bool True if the value was fixed, false if not.
     * 
     */
    protected function sanitize()
    {
        $this->setValue(preg_replace('/\W/', '', $this->getValue()));
    }
}
