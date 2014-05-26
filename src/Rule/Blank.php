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
 * Validates that a value is blank (null, empty string, or string of only 
 * whitespace characters).
 * 
 * @package Aura.Filter
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
class Blank extends AbstractRule
{
    /**
     * 
     * Messages to use when validate or sanitize fails.
     *
     * @var array
     * 
     */
    protected $message_map = [
        'failure_is'            => 'FILTER_RULE_FAILURE_IS_BLANK',
        'failure_is_not'        => 'FILTER_RULE_FAILURE_IS_NOT_BLANK',
        'failure_is_blank_or'   => 'FILTER_RULE_FAILURE_IS_BLANK_OR_BLANK',
        'failure_fix'           => 'FILTER_RULE_FAILURE_FIX_BLANK',
        'failure_fix_blank_or'  => 'FILTER_RULE_FAILURE_FIX_BLANK_OR_BLANK',
    ];

    /**
     * 
     * Validates that the value is null, or is a string composed only of
     * whitespace.
     * 
     * Non-strings and non-nulls never validate as blank; this includes
     * integers, floats, numeric zero, boolean true and false, any array with
     * zero or more elements, and all objects and resources.
     * 
     * @return bool True if valid, false if not.
     * 
     */
    public function validate()
    {
        return $this->isBlank();
    }

    /**
     * 
     * Set value to null
     * 
     * @return bool Always true.
     * 
     */
    public function sanitize()
    {
        $this->setValue(null);
        return true;
    }
}
