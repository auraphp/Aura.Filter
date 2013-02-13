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
 * Validates that the value is a key in the list of allowed options.
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class InKeys extends AbstractRule
{
    /**
     * 
     * Messages to use when validate or sanitize fails.
     *
     * @var array
     * 
     */
    protected $message_map = [
        'failure_is'            => 'FILTER_RULE_FAILURE_IS_IN_KEYS',
        'failure_is_not'        => 'FILTER_RULE_FAILURE_IS_NOT_IN_KEYS',
        'failure_is_blank_or'   => 'FILTER_RULE_FAILURE_IS_BLANK_OR_IN_KEYS',
        'failure_fix'           => 'FILTER_RULE_FAILURE_FIX_IN_KEYS',
        'failure_fix_blank_or'  => 'FILTER_RULE_FAILURE_FIX_BLANK_OR_IN_KEYS',
    ];

    /**
     * 
     * Validates that the value is a key in a given array.
     * 
     * Given an array (second parameter), the value (first parameter) must 
     * match at least one of the array keys.
     * 
     * Strict checking is enforced, so a string "1" is not the same as
     * an integer 1.  This helps to avoid matching between 0, false, null,
     * and empty string.
     * 
     * @param array $array An array of key-value pairs; the value must match
     * one of the keys in this array.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    public function validate(array $array)
    {
        $keys = array_keys($array);
        $this->setParams(['keys' => $keys]);
        return in_array($this->getValue(), $keys, true);
    }

    /**
     * 
     * Cannot fix the value.
     * 
     * @return boolean
     * 
     */
    public function sanitize()
    {
        return false;
    }
}
