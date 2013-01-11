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
 * Sanitizes a value to a string using trim().
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class Trim extends AbstractRule
{
    /**
     *
     * Error message
     * 
     * @var string
     */
    protected $message = 'FILTER_TRIM';

    /**
     * 
     * same as PHP trim()
     *
     * @var string
     */
    protected $chars = " \t\n\r\0\x0B";

    /**
     * 
     * Is the value already trimmed?
     * 
     * @param string $chars
     * 
     * @return bool True if valid, false if not.
     * 
     */
    public function validate($chars = null)
    {
        $value = $this->getValue();
        if (! is_scalar($value)) {
            return false;
        }
        if (! $chars) {
            $chars = $this->chars;
        }
        return trim($value, $chars) == $value;
    }

    /**
     * 
     * Trims characters from the beginning and end of the value.
     * 
     * @param string $chars
     * 
     * @return bool True if the value was fixed, false if not.
     * 
     */
    public function sanitize($chars = null)
    {
        $value = $this->getValue();
        if (! is_scalar($value)) {
            return false;
        }
        if (! $chars) {
            $chars = $this->chars;
        }
        $this->setValue(trim($value, $chars));
        return true;
    }
}
