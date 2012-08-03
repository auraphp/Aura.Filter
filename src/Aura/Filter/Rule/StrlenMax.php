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
class StrlenMax extends AbstractRule
{
    protected $message = 'FILTER_STRLEN_MAX';
    
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
    protected function validate($max)
    {
        return strlen($this->getValue()) <= $max;
    }
    
    protected function sanitize($max)
    {
        $value = $this->getValue();
        if (strlen($value) > $max) {
            $this->setValue(substr($value, 0, $max));
        }
        return true;
    }
}
