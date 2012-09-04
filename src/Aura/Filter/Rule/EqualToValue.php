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
 * Validates that this value is equal to some other element in the filter 
 * chain (note that equality is not strict, so type does not matter).
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class EqualToValue extends AbstractRule
{
    /**
     *
     * Error message
     * 
     * @var string
     */
    protected $message = 'FILTER_EQUAL_TO_VALUE';

    /**
     * 
     * check whether the value is equal
     * 
     * @param mixed $other_value
     * 
     * @return boolean
     */
    protected function validate($other_value)
    {
        return $this->getValue() == $other_value;
    }

    /**
     * 
     * force the field to the value of the other field
     * 
     * @param mixed $other_value
     * 
     * @return boolean
     */
    protected function sanitize($other_value)
    {
        $this->setValue($other_value);
        return true;
    }
}
