<?php
namespace Aura\Filter\Rule;

/**
 * 
 * Validates that a value is no longer than a certain length.
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class StrlenMin extends AbstractRule
{
    protected $message = 'FILTER_STRLEN_MIN';
    
    /**
     * 
     * Validates that a string is no longer than a certain length.
     * 
     * @param mixed $max The value must have no more than this many
     * characters.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    protected function validate($min)
    {
        return strlen($this->getValue()) >= $min;
    }
    
    protected function sanitize($min, $pad_string = ' ', $pad_type = STR_PAD_RIGHT)
    {
        $value = $this->getValue();
        if (strlen($value) < $min) {
            $this->setValue(str_pad($value, $min, $pad_string, $pad_type));
        }
        return true;
    }
}
