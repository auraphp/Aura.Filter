<?php
/**
 *
 * This file is part of the Aura project for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Rule;

use Aura\Filter\AbstractRule;

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
     * Messages to use when validate or sanitize fails.
     *
     * @var array
     *
     */
    protected $message_map = [
        'failure_is'            => 'FILTER_RULE_FAILURE_IS_STRLEN_MIN',
        'failure_is_not'        => 'FILTER_RULE_FAILURE_IS_NOT_STRLEN_MIN',
        'failure_is_blank_or'   => 'FILTER_RULE_FAILURE_IS_BLANK_OR_STRLEN_MIN',
        'failure_fix'           => 'FILTER_RULE_FAILURE_FIX_STRLEN_MIN',
        'failure_fix_blank_or'  => 'FILTER_RULE_FAILURE_FIX_BLANK_OR_STRLEN_MIN',
    ];

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
    public function validate($min)
    {
        $this->setParams(get_defined_vars());
        $value = $this->getValue();
        if (! is_scalar($value)) {
            return false;
        }
        return strlen($value) >= $min;
    }

    /**
     *
     * Fix to min length
     *
     * @param int $min
     *
     * @param string $pad_string
     *
     * @param constant $pad_type
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function sanitize($min, $pad_string = ' ', $pad_type = STR_PAD_RIGHT)
    {
        $this->setParams(get_defined_vars());
        $value = $this->getValue();
        if (! is_scalar($value)) {
            return false;
        }
        if (strlen($value) < $min) {
            $this->setValue(str_pad($value, $min, $pad_string, $pad_type));
        }
        return true;
    }
}
