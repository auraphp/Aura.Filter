<?php
namespace Aura\Filter\Rule;

/**
 * 
 * Sanitizes a value to an IPv4 address.
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class Ipv4 extends AbstractRule
{
    protected $message = 'FILTER_IPV4';
    
    /**
     * 
     * Validates that the value is a legal IPv4 address.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    protected function validate()
    {
        $value = $this->getValue();
        
        // does the value convert back and forth properly?
        $result = ip2long($value);
        if ($result == -1 || $result === false) {
            // does not properly convert to a "long" result
            return false;
        } elseif (long2ip($result) !== $value) {
            // the long result does not convert back to an identical original
            // value
            return false;
        } else {
            // looks valid
            return true;
        }
    }
    
    /**
     * 
     * Forces the value to an IPv4 address.
     * 
     * @return bool True if the value was fixed, false if not.
     * 
     */
    protected function sanitize()
    {
        return false; // can't fix IP addresses
    }
}
