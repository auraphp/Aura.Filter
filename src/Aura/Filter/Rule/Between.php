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

use Aura\Filter\AbstractRule;

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
    /**
     * 
     * Error message
     *
     * @var string
     */
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
        if (! is_scalar($value)) {
            return false;
        }
        return ($value >= $min && $value <= $max);
    }

    /**
     * 
     * If the value is < min , will set the min value,
     * and if value is greater than max, set the max value
     * 
     * @param mixed $min The minimum valid value.
     * 
     * @param mixed $max The maximum valid value.
     * 
     * @return bool
     * 
     */
    protected function sanitize($min, $max)
    {
        $value = $this->getValue();
        if (! is_scalar($value)) {
            return false;
        }
        if ($value < $min) {
            $this->setValue($min);
        } elseif ($value > $max) {
            $this->setValue($max);
        }
        return true;
    }
}
