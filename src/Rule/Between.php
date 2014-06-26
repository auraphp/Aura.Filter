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
 * Validates that a value is within a given range.
 *
 * @package Aura.Filter
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
class Between extends AbstractRule
{
    /**
     *
     * Messages to use when validate or sanitize fails.
     *
     * @var array
     *
     */
    protected $message_map = [
        'failure_is'            => 'FILTER_RULE_FAILURE_IS_BETWEEN',
        'failure_is_not'        => 'FILTER_RULE_FAILURE_IS_NOT_BETWEEN',
        'failure_is_blank_or'   => 'FILTER_RULE_FAILURE_IS_BLANK_OR_BETWEEN',
        'failure_fix'           => 'FILTER_RULE_FAILURE_FIX_BETWEEN',
        'failure_fix_blank_or'  => 'FILTER_RULE_FAILURE_FIX_BLANK_OR_BETWEEN',
    ];

    /**
     *
     * Validates that the value is within a given range.
     *
     * @param mixed $min The minimum valid value.
     *
     * @param mixed $max The maximum valid value.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function validate($min, $max)
    {
        $this->setParams(get_defined_vars());

        $value = $this->getValue();

        if (! is_scalar($value)) {
            return false;
        }

        return ($value >= $min && $value <= $max);
    }

    /**
     *
     * If the value is < min , will set the min value,
     * and if value is greater than max, set the max value
     *
     * @param mixed $min The minimum valid value.
     *
     * @param mixed $max The maximum valid value.
     *
     * @return bool True if the value was sanitized, false if not.
     *
     */
    public function sanitize($min, $max)
    {
        $this->setParams(get_defined_vars());

        $value = $this->getValue();

        if (! is_scalar($value)) {
            return false;
        }

        if ($value < $min) {
            $this->setValue($min);
        } elseif ($value > $max) {
            $this->setValue($max);
        }

        return true;
    }
}
