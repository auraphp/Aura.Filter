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
 * Validates that this value is equal to some other element in the filter
 * chain (note that equality is not strict, so type does not matter).
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class StrictEqualToValue extends AbstractRule
{
    /**
     *
     * Messages to use when validate or sanitize fails.
     *
     * @var array
     *
     */
    protected $message_map = [
        'failure_is'            => 'FILTER_RULE_FAILURE_IS_STRICT_EQUAL_TO_VALUE',
        'failure_is_not'        => 'FILTER_RULE_FAILURE_IS_NOT_STRICT_EQUAL_TO_VALUE',
        'failure_is_blank_or'   => 'FILTER_RULE_FAILURE_IS_BLANK_OR_STRICT_EQUAL_TO_VALUE',
        'failure_fix'           => 'FILTER_RULE_FAILURE_FIX_STRICT_EQUAL_TO_VALUE',
        'failure_fix_blank_or'  => 'FILTER_RULE_FAILURE_FIX_BLANK_OR_STRICT_EQUAL_TO_VALUE',
    ];

    /**
     *
     * Check the values strictly on type and value
     *
     * @param string $other_value
     *
     * @return bool True if the values are equal, false if not equal.
     *
     */
    public function validate($other_value)
    {
        $this->setParams(get_defined_vars());

        return $this->getValue() === $other_value;
    }

    /**
     *
     * Force the field to the value of the other field
     *
     * @param string $other_value
     *
     * @return bool Always true.
     *
     */
    public function sanitize($other_value)
    {
        $this->setParams(get_defined_vars());
        $this->setValue($other_value);

        return true;
    }
}
