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
 * Validates that a value's length is within a given range.
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class Strlen extends AbstractRule
{
    /**
     *
     * Error message
     * 
     * @var string
     */
    protected $message = 'FILTER_STRLEN';

    /**
     * 
     * Validates that the length of the value is within a given range.
     * 
     * @param mixed $len The minimum valid length.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    protected function validate($len)
    {
        $value = $this->getValue();
        if (! is_scalar($value)) {
            return false;
        }
        return strlen($value) == $len;
    }

    /**
     * 
     * Sanitize to the length given
     * 
     * @param int $len
     * 
     * @param int $pad_string
     * 
     * @param constant $pad_type
     * 
     * @return boolean
     */
    protected function sanitize($len, $pad_string = ' ', $pad_type = STR_PAD_RIGHT)
    {
        $value = $this->getValue();
        if (! is_scalar($value)) {
            return false;
        }
        if (strlen($value) < $len) {
            $this->setValue(str_pad($value, $len, $pad_string, $pad_type));
        }
        if (strlen($value) > $len) {
            $this->setValue(substr($value, 0, $len));
        }
        return true;
    }
}
