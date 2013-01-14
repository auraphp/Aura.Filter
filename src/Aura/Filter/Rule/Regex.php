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
 * Validates a value using preg_match(), and sanitizes a value to a string
 * using preg_replace().
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class Regex extends AbstractRule
{
    /**
     *
     * Error message
     * 
     * @var string
     */
    protected $message = 'FILTER_REGEX';

    /**
     * 
     * Validates the value against a regular expression.
     * 
     * Uses [[php::preg_match() | ]] to compare the value against the given
     * regular expression.
     * 
     * @param string $expr The regular expression to validate against.
     * 
     * @return bool True if the value matches the expression, false if not.
     * 
     */
    public function validate($expr)
    {
        $value = $this->getValue();
        if (! is_scalar($value)) {
            return false;
        }
        return (bool) preg_match($expr, $value);
    }

    /**
     * 
     * Applies [[php::preg_replace() | ]] to the value.
     * 
     * @param string $expr The regular expression pattern to apply.
     * 
     * @param string $replace Replace the found pattern with this string.
     * 
     * @return bool True if the value was fixed, false if not.
     * 
     */
    public function sanitize($expr, $replace)
    {
        $value = $this->getValue();
        if (! is_scalar($value)) {
            return false;
        }
        $this->setValue(preg_replace($expr, $replace, $value));
        return true;
    }
}
