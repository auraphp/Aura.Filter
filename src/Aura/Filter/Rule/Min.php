<?php
namespace Aura\Filter\Rule;

/**
 * 
 * Validates that a value is greater than or equal to a minimum.
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class Min extends AbstractRule
{
    protected $message = 'FILTER_MIN';
    
    /**
     * 
     * Validates that the value is greater than or equal to a minimum.
     * 
     * @param mixed $min The minimum valid value.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    protected function validate($min)
    {
        return $this->getValue() >= $min;
    }
    
    protected function sanitize($min)
    {
        $value = $this->getValue();
        if ($value < $min) {
            $this->setValue($min);
        }
        return true;
    }
}
