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
 * Sanitizes a value to an IPv4 address.
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class Ipv4 extends AbstractRule
{
    /**
     * 
     * Messages to use when validate or sanitize fails.
     *
     * @var array
     * 
     */
    protected $message_map = [
        'failure_is'            => 'FILTER_RULE_FAILURE_IS_IPV4',
        'failure_is_not'        => 'FILTER_RULE_FAILURE_IS_NOT_IPV4',
        'failure_is_blank_or'   => 'FILTER_RULE_FAILURE_IS_BLANK_OR_IPV4',
        'failure_fix'           => 'FILTER_RULE_FAILURE_FIX_IPV4',
        'failure_fix_blank_or'  => 'FILTER_RULE_FAILURE_FIX_BLANK_OR_IPV4',
    ];

    /**
     * 
     * Validates that the value is a legal IPv4 address.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    public function validate()
    {
        $value = $this->getValue();

        // does the value convert back and forth properly?
        $result = ip2long($value);
        if ($result == -1 || $result === false) {
            // does not properly convert to a "long" result
            return false;
        } else {
            // looks valid
            return true;
        }
    }

    /**
     * 
     * Forces the value to an IPv4 address.
     * 
     * @return bool Always false.
     * 
     */
    public function sanitize()
    {
        return false; // can't fix IP addresses
    }
}
