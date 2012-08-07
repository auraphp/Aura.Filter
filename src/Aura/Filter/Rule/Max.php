<?php
/**
 * 
 * This file is part of the Aura project for PHP.
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
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
    /**
     * 
     * Error message
     *
     * @var string
     */
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

    /**
     * 
     * Sanitizes to maximum value if values is greater than max
     * 
     * @param mixed $max The maximum valid value.
     * 
     * @return boolean
     */
    protected function sanitize($max)
    {
        $value = $this->getValue();
        if ($value > $max) {
            $this->setValue($max);
        }
        return true;
    }
}

