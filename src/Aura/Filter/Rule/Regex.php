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
     * Messages to use when validate or sanitize fails.
     *
     * @var array
     * 
     */
    protected $message_map = [
        'failure_is'            => 'FILTER_RULE_FAILURE_IS_REGEX',
        'failure_is_not'        => 'FILTER_RULE_FAILURE_IS_NOT_REGEX',
        'failure_is_blank_or'   => 'FILTER_RULE_FAILURE_IS_BLANK_OR_REGEX',
        'failure_fix'           => 'FILTER_RULE_FAILURE_FIX_REGEX',
        'failure_fix_blank_or'  => 'FILTER_RULE_FAILURE_FIX_BLANK_OR_REGEX',
    ];

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
        $this->setParams(get_defined_vars());
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
        $this->setParams(get_defined_vars());
        $value = $this->getValue();
        if (! is_scalar($value)) {
            return false;
        }
        $this->setValue(preg_replace($expr, $replace, $value));
        return true;
    }
}
