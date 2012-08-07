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
 * Validates that a value is no longer than a certain length.
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class StrlenMin extends AbstractRule
{
    /**
     *
     * Error message
     * 
     * @var string
     */
    protected $message = 'FILTER_STRLEN_MIN';

    /**
     * 
     * Validates that a string is no longer than a certain length.
     * 
     * @param mixed $min The value must have no more than this many
     * characters.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    protected function validate($min)
    {
        return strlen($this->getValue()) >= $min;
    }

    /**
     * 
     * fix to min length
     * 
     * @param int $min
     * 
     * @param string $pad_string
     * 
     * @param constant $pad_type
     * 
     * @return boolean
     */
    protected function sanitize($min, $pad_string = ' ', $pad_type = STR_PAD_RIGHT)
    {
        $value = $this->getValue();
        if (strlen($value) < $min) {
            $this->setValue(str_pad($value, $min, $pad_string, $pad_type));
        }
        return true;
    }
}

