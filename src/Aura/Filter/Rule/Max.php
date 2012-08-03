<?php
namespace Aura\Filter\Rule;

/**
 * 
 * Validates that a value is less than than or equal to a maximum.
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class Max extends AbstractRule
{
    protected $message = 'FILTER_MAX';
    
    /**
     * 
     * Validates that the value is less than than or equal to a maximum.
     * 
     * @param mixed $max The maximum valid value.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    protected function validate($max)
    {
        return $this->getValue() <= $max;
    }
    
    protected function sanitize($max)
    {
        $value = $this->getValue();
        if ($value > $max) {
            $this->setValue($max);
        }
        return true;
    }
}
