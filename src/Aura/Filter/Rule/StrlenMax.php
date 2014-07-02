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
 * Validates that a value is no longer than a certain length.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class StrlenMax extends AbstractRule
{
    /**
     *
     * Messages to use when validate or sanitize fails.
     *
     * @var array
     *
     */
    protected $message_map = [
        'failure_is'            => 'FILTER_RULE_FAILURE_IS_STRLEN_MAX',
        'failure_is_not'        => 'FILTER_RULE_FAILURE_IS_NOT_STRLEN_MAX',
        'failure_is_blank_or'   => 'FILTER_RULE_FAILURE_IS_BLANK_OR_STRLEN_MAX',
        'failure_fix'           => 'FILTER_RULE_FAILURE_FIX_STRLEN_MAX',
        'failure_fix_blank_or'  => 'FILTER_RULE_FAILURE_FIX_BLANK_OR_STRLEN_MAX',
    ];

    /**
     *
     * Validates that a string is no longer than a certain length.
     *
     * @param mixed $max The value must have no more than this many
     * characters.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function validate($max)
    {
        $this->setParams(get_defined_vars());
        $value = $this->getValue();
        if (! is_scalar($value)) {
            return false;
        }
        return strlen($value) <= $max;
    }

    /**
     *
     * If the value is greater than max, set to max value
     *
     * @param int $max
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function sanitize($max)
    {
        $this->setParams(get_defined_vars());
        $value = $this->getValue();
        if (! is_scalar($value)) {
            return false;
        }
        if (strlen($value) > $max) {
            $this->setValue(substr($value, 0, $max));
        }
        return true;
    }
}
