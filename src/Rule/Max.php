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
 * Validates that a value is less than than or equal to a maximum.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Max extends AbstractRule
{
    /**
     *
     * Messages to use when validate or sanitize fails.
     *
     * @var array
     *
     */
    protected $message_map = [
        'failure_is'            => 'FILTER_RULE_FAILURE_IS_MAX',
        'failure_is_not'        => 'FILTER_RULE_FAILURE_IS_NOT_MAX',
        'failure_is_blank_or'   => 'FILTER_RULE_FAILURE_IS_BLANK_OR_MAX',
        'failure_fix'           => 'FILTER_RULE_FAILURE_FIX_MAX',
        'failure_fix_blank_or'  => 'FILTER_RULE_FAILURE_FIX_BLANK_OR_MAX',
    ];

    /**
     *
     * Validates that the value is less than than or equal to a maximum.
     *
     * @param mixed $max The maximum valid value.
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

        return $value <= $max;
    }

    /**
     *
     * Sanitizes to maximum value if values is greater than max
     *
     * @param mixed $max The maximum valid value.
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
        if ($value > $max) {
            $this->setValue($max);
        }

        return true;
    }
}
