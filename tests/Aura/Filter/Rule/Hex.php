<?php
namespace Aura\Filter\Rule;

use Aura\Filter\AbstractRule;

class Hex extends AbstractRule
{
    protected $message = 'FILTER_HEX';
    
    protected function validate($max = null)
    {
        // must be scalar
        $value = $this->getValue();
        if (! is_scalar($value)) {
            return false;
        }
        
        // must be hex
        $hex = ctype_xdigit($value);
        if (! $hex) {
            return false;
        }
        
        // must be no longer than $max chars
        if ($max && strlen($value) > $max) {
            return false;
        }
        
        // done!
        return true;
    }
    
    protected function sanitize($max = null)
    {
        // must be scalar
        $value = $this->getValue();
        if (! is_scalar($value)) {
            // sanitizing failed
            return false;
        }
        
        // strip out non-hex characters
        $value = preg_replace('/[^0-9a-f]/i', '', $value);
        if ($value === '') {
            // failed to sanitize to a hex value
            return false;
        }
        
        // now check length and chop if needed
        if ($max && strlen($value) > $max) {
            $value = substr($value, 0, $max);
        }
        
        // retain the sanitized value, and done!
        $this->setValue($value);
        return true;
    }
}
