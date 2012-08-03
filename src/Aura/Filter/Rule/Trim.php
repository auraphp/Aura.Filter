<?php
namespace Aura\Filter\Rule;

/**
 * 
 * Sanitizes a value to a string using trim().
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class Trim extends AbstractRule
{
    protected $message = 'FILTER_TRIM';
    
    // same as PHP trim()
    protected $chars = " \t\n\r\0\x0B";
    
    /**
     * 
     * Is the value already trimmed?
     * 
     * @return bool True if valid, false if not.
     * 
     */
    protected function validate($chars = null)
    {
        $value = $this->getValue();
        if (! $chars) {
            $chars = $this->chars;
        }
        return trim($value, $chars) == $value;
    }
    
    /**
     * 
     * Trims characters from the beginning and end of the value.
     * 
     * @return bool True if the value was fixed, false if not.
     * 
     */
    public function sanitize($chars = null)
    {
        if (! $chars) {
            $chars = $this->chars;
        }
        $this->setValue(trim($this->getValue(), $chars));
    }
}
