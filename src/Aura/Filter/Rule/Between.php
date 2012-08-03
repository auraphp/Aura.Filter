<?php
namespace Aura\Filter\Rule;

/**
 * 
 * Validates that a value is within a given range.
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class Between extends AbstractRule
{
    protected $message = 'FILTER_BETWEEN';
    
    /**
     * 
     * Validates that the value is within a given range.
     * 
     * @param mixed $min The minimum valid value.
     * 
     * @param mixed $max The maximum valid value.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    protected function validate($min, $max)
    {
        $value = $this->getValue();
        return ($value >= $min && $value <= $max);
    }
    
    protected function sanitize($min, $max)
    {
        $value = $this->getValue();
        if ($value < $min) {
            $this->setValue($min);
        } elseif ($value > $max) {
            $this->setValue($max);
        }
        return true;
    }
}
