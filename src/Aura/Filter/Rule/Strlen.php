<?php
namespace Aura\Filter\Rule;

/**
 * 
 * Validates that a value's length is within a given range.
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class Strlen extends AbstractRule
{
    protected $message = 'FILTER_STRLEN';
    
    /**
     * 
     * Validates that the length of the value is within a given range.
     * 
     * @param mixed $min The minimum valid length.
     * 
     * @param mixed $max The maximum valid length.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    protected function validate($len)
    {
        return strlen($this->getValue()) == $len;
    }
    
    protected function sanitize($len, $pad_string = ' ', $pad_type = STR_PAD_RIGHT)
    {
        $value = $this->getValue();
        if (strlen($value) < $len) {
            $this->setValue(str_pad($value, $len, $pad_string, $pad_type));
        }
        if (strlen($value) > $len) {
            $this->setValue(substr($value, 0, $len));
        }
        return true;
    }
}
