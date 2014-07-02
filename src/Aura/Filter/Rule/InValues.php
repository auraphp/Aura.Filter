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
 * Validates that a value is in a list of allowed values.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class InValues extends AbstractRule
{
    /**
     *
     * Messages to use when validate or sanitize fails.
     *
     * @var array
     *
     */
    protected $message_map = [
        'failure_is'            => 'FILTER_RULE_FAILURE_IS_IN_VALUES',
        'failure_is_not'        => 'FILTER_RULE_FAILURE_IS_NOT_IN_VALUES',
        'failure_is_blank_or'   => 'FILTER_RULE_FAILURE_IS_BLANK_OR_IN_VALUES',
        'failure_fix'           => 'FILTER_RULE_FAILURE_FIX_IN_VALUES',
        'failure_fix_blank_or'  => 'FILTER_RULE_FAILURE_FIX_BLANK_OR_IN_VALUES',
    ];

    /**
     *
     * Validates that the value is in a given array.
     *
     * Strict checking is enforced, so a string "1" is not the same as
     * an integer 1.  This helps to avoid matching between 0, false, null,
     * and empty string.
     *
     * @param array $array An array of allowed values.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function validate(array $array)
    {
        $this->setParams(['values' => array_values($array)]);
        return in_array($this->getValue(), $array, true);
    }

    /**
     *
     * Cannot fix the value.
     *
     * @return bool Always false.
     *
     */
    public function sanitize()
    {
        return false;
    }
}
