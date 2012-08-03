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
class StrlenBetween extends AbstractRule
{
    protected $message = 'FILTER_STRLEN_BETWEEN';
    
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
    protected function validate($min, $max)
    {
        $len = strlen($this->getValue());
        return ($len >= $min && $len <= $max);
    }
    
    protected function sanitize($min, $max, $pad_string = ' ', $pad_type = STR_PAD_RIGHT)
    {
        $value = $this->getValue();
        if (strlen($value) < $min) {
            $this->setValue(str_pad($value, $min, $pad_string, $pad_type));
        }
        if (strlen($value) > $max) {
            $this->setValue(substr($value, 0, $max));
        }
        return true;
    }
}
