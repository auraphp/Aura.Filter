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
 * Validates that a value is greater than or equal to a minimum.
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class Min extends AbstractRule
{
    /**
     *
     * Error message
     * 
     * @var string
     */
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

    /**
     * 
     * check whether the value is less than min, if so set to min
     * 
     * @param int $min
     * 
     * @return boolean
     */
    protected function sanitize($min)
    {
        $value = $this->getValue();
        if ($value < $min) {
            $this->setValue($min);
        }
        return true;
    }
}

